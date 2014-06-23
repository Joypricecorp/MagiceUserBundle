<?php
namespace Magice\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseResettingController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;

class ResettingController extends BaseResettingController
{
    public function testAction()
    {
        return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:test.html.twig');
    }
    /**
     * Request reset user password: submit form and send email
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');

        /**
         * @var $user UserInterface
         */
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->container->get('templating')->renderResponse(
                'FOSUserBundle:Resetting:request.html.' . $this->getEngine(),
                array('invalid_username' => $username)
            );
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:passwordAlreadyRequested.html.' . $this->getEngine());
        }

        if (null === $user->getConfirmationToken()) {
            /**
             * @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface
             */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new RedirectResponse($this->container->get('router')->generate(
            'mg_user_resetting_check_email',
            array('email' => $this->getObfuscatedEmail($user))
        ));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction(Request $request)
    {
        $email = $request->query->get('email');

        if (empty($email)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->container->get('router')->generate('mg_user_resetting_request'));
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Resetting:checkEmail.html.' . $this->getEngine(),
            array(
                'email' => $email,
            )
        );
    }
}