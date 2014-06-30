<?php
namespace Magice\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="mg_user_connect",
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"user_id", "provider"})}
 * )
 */
class UserConnect
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int provider user id
     * @ORM\Column(name="pu_id", type="bigint", nullable=false)
     */
    private $pid;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", nullable=true)
     */
    private $username;

    /**
     * @var string fullname
     * @ORM\Column(name="fullname", type="string", nullable=true)
     */
    private $fullname;

    /**
     * @var string
     * @ORM\Column(name="nickname", type="string", nullable=true)
     */
    private $nickname;

    /**
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    private $accessToken;

    /**
     * @var \DateTime
     * @ORM\Column(name="access_token_expire", type="datetime")
     */
    private $accessTokenExpire;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
     */
    private $email;

    /**
     * @var string avatar url
     * @ORM\Column(name="avartar", type="string", nullable=true)
     */
    private $avatar;

    /**
     * @var string profile url
     * @ORM\Column(name="profile", type="string", nullable=true)
     */
    private $profile;

    /**
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     */
    private $locale;

    /**
     * @ORM\Column(name="location", type="string", length=50, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @var \DateTime
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(name="provider", type="string", length=20, nullable=true)
     */
    private $provider;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Magice\Bundle\UserBundle\Entity\User", inversedBy="connects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $pid
     *
     * @return $this
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param mixed $accessToken
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param \DateTime $accessTokenExpire
     *
     * @return $this
     */
    public function setAccessTokenExpire($accessTokenExpire)
    {
        $this->accessTokenExpire = $accessTokenExpire;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAccessTokenExpire()
    {
        return $this->accessTokenExpire;
    }

    /**
     * @param mixed $avatar
     *
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $locale
     *
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $location
     *
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $nickname
     *
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $profile
     *
     * @return $this
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $provider
     *
     * @return $this
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $fullname
     *
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param \DateTime $birthday
     *
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
}
