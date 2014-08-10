<?php
namespace Magice\Bundle\UserBundle\Model;

use Magice\Bundle\UserBundle\Event\Connect;
use Magice\Bundle\UserBundle\OAuth\Response\ResponseInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider extends FOSUBUserProvider implements UserProviderInterface
{
    /**
     * @var UserConnectManagerInterface|UserManagerInterface
     */
    protected $userManager;

    /**
     * @var array
     */
    protected $container;

    /**
     * @var string
     */
    protected $userInfoClass;

    /**
     * Constructor.
     *
     * @param UserConnectManagerInterface $userManager FOSUB user provider.
     * @param ContainerInterface          $container
     * @param string                      $userInfoClass
     */
    public function __construct(UserConnectManagerInterface $userManager, ContainerInterface $container, $userInfoClass)
    {
        $this->userManager   = $userManager;
        $this->container     = $container;
        $this->userInfoClass = $userInfoClass;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        /**
         * @var User $user
         */
        $this->updateConnect($response, $user, $user->getConnect($response->getResourceOwner()->getName()));
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        /**
         * @var ResponseInterface|UserResponseInterface $response
         * @var User|UserInterface                      $user
         * @var EventDispatcherInterface                $dispatcher
         */
        $provider = $response->getResourceOwner()->getName();
        $user     = $this->userManager->findUserConnectByEmail($provider, $response->getEmail());

        // try to find by username
        if (null === $user) {
            $user = $this->userManager->findUserConnectByUsername($provider, $response->getUsername());
        }

        // try to find form user table
        // this case when manaual register first and later he/she login via social connect
        // this will ignore find by username
        if (null === $user) {
            // to salfty use email to find
            $user = $this->findUser($response->getEmail());
        }

        // not user
        if (null === $user) {
            $user = $this->createDummyUser($response->getUsername(), $response->getEmail(), true, false);

            $this->addInfo($response, $user);
            $this->updateConnect($response, $user, null);
            $this->userManager->updateUser($user);

            $event = new Connect($user, $response);

            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch(Connect::ON_CONNECT_NEW, $event);

            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $this->addInfo($response, $user);
        $this->updateConnect($response, $user, $user->getConnect($provider));
        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * @param $userNameOrEmail
     *
     * @return User|\FOS\UserBundle\Model\UserInterface
     */
    public function findUser($userNameOrEmail)
    {
        return $this->userManager->findUserByUsernameOrEmail($userNameOrEmail);
    }

    /**
     * @param string $username
     * @param string $email
     * @param bool   $enable
     * @param bool   $flush
     *
     * @return User|\FOS\UserBundle\Model\UserInterface
     * @throws \Exception
     */
    public function createDummyUser($username, $email, $enable = true, $flush = true)
    {
        try {

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Invalid email format to create dummy user.');
            }

            $manager = $this->userManager;
            $user    = $manager->createUser();

            $user
                ->setEnabled($enable)
                ->setUsername($username)
                ->setEmail($email)
                ->setPlainPassword($this->generateToken(12))
                ->setConfirmationToken($this->generateToken());

            $manager->updateUser($user, $flush);

            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function generateToken($len = false)
    {
        $token = $this->container->get('fos_user.util.token_generator')->generateToken();

        if ($len) {
            return substr($token, 0, $len);
        } else {
            return $token;
        }
    }

    /**
     * @param ResponseInterface $response
     * @param User              $user
     * @param UserConnect       $connect
     */
    protected function updateConnect(ResponseInterface $response, User $user, UserConnect $connect = null)
    {
        $connect = $connect ? : new UserConnect();

        $connect
            ->setProvider($response->getProvider())
            ->setPid($response->getId())

            ->setGender($response->getGender())
            ->setFullname($response->getRealName())
            ->setNickname($response->getNickname())
            ->setBirthday($response->getBirthday())
            ->setAvatar($response->getProfilePicture())
            ->setProfile($response->getProfile())

            ->setLocale($response->getLocale())
            ->setLocation($response->getLocation())

            ->setUser($user)
            ->setUsername($response->getUsername())
            ->setEmail($response->getEmail())

            ->setAccessToken($response->getAccessToken())
            ->setAccessTokenExpire($response->getExpiresIn(true));

        $user->setConnect($response->getProvider(), $connect);
    }

    /**
     * @param ResponseInterface $response
     * @param User              $user
     */
    protected function addInfo(ResponseInterface $response, User $user)
    {
        // if have info nothing to to
        // use must update their info by self
        if ($user->getInfo()) {
            return;
        }

        $class = $this->userInfoClass;
        $info  = (new $class())
            ->setUser($user)
            ->setAvatar($response->getProfilePicture())
            ->setDisplayName($response->getNickname())
            ->setBirthday($response->getBirthday())
            ->setGender($response->getGender())
            ->setFirstname($response->getFirstName())
            ->setLastname($response->getLastName());

        $user->setInfo($info);
    }
}
