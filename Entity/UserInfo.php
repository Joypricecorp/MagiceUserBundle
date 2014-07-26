<?php

namespace Magice\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use libphonenumber\PhoneNumber;

/**
 * @ORM\Table(name="mg_user_info")
 * @ORM\Entity
 */
class UserInfo
{
    const YEAR_BC_DIFF = 543;
    const YEAR_MIN_AGE = 13;
    const YEAR_MAG_AGE = 60;

    use TimestampableEntity;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(name="firstname", type="string", length=50)
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="lastname", type="string", length=50)
     */
    private $lastname;

    /**
     * @ORM\Column(name="display_name", type="string", length=50, nullable=true)
     */
    private $displayName;

    /**
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     * @ORM\Column(name="personal_id", type="string", length=13, nullable=true)
     */
    private $personalId;

    /**
     * @var \DateTime
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var PhoneNumber
     * @ORM\Column(name="mobile", type="phone_number", nullable=true)
     */
    private $mobile;

    /**
     * @var PhoneNumber
     * @ORM\Column(name="tel_home", type="phone_number", nullable=true)
     */
    private $telHome;

    /**
     * @var PhoneNumber
     * @ORM\Column(name="tel_work", type="phone_number", nullable=true)
     */
    private $telWork;

    /**
     * @var string
     * @ORM\Column(name="tel_work_ext", type="string", length=5, nullable=true)
     */
    private $telWorkExt;

    /**
     * @var string public email
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="Magice\Bundle\UserBundle\Entity\User", inversedBy="info")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $user;

    /**
     * @var string
     * for JMS\Accessor(getter="getFullname")
     */
    private $fullname;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = trim($firstname);

        return $this;
    }

    /**
     * Get firstname
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set Lastname
     *
     * @param string $lastname
     *
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = trim($lastname);

        return $this;
    }

    /**
     * Get lastname
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $displayName
     *
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = trim($displayName);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName ? : $this->getFullname();
    }

    /**
     * @param $avatar
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
     * Set personalId
     *
     * @param integer $personalId
     *
     * @return $this
     */
    public function setPersonalId($personalId)
    {
        $this->personalId = trim($personalId);

        return $this;
    }

    /**
     * Get personalId
     * @return integer
     */
    public function getPersonalId()
    {
        return $this->personalId;
    }

    /**
     * Set birthday
     *
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
     * Get birthday
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set infmobile
     *
     * @param PhoneNumber $mobile
     *
     * @return $this
     */
    public function setMobile(PhoneNumber $mobile = null)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get infmobile
     * @return PhoneNumber
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set inftelHome
     *
     * @param PhoneNumber $telHome
     *
     * @return $this
     */
    public function setTelHome(PhoneNumber $telHome = null)
    {
        $this->telHome = $telHome;

        return $this;
    }

    /**
     * Get inftelHome
     * @return PhoneNumber
     */
    public function getTelHome()
    {
        return $this->telHome;
    }

    /**
     * Set inftelWork
     *
     * @param PhoneNumber $telWork
     *
     * @return $this
     */
    public function setTelWork(PhoneNumber $telWork = null)
    {
        $this->telWork = $telWork;

        return $this;
    }

    /**
     * Get inftelWork
     * @return PhoneNumber
     */
    public function getTelWork()
    {
        return $this->telWork;
    }

    /**
     * Set inftelWorkExt
     *
     * @param string $telWorkExt
     *
     * @return $this
     */
    public function setTelWorkExt($telWorkExt)
    {
        $this->telWorkExt = $telWorkExt;

        return $this;
    }

    /**
     * Get inftelWorkExt
     * @return string
     */
    public function getTelWorkExt()
    {
        return $this->telWorkExt;
    }

    public function getTelWorkWithExt()
    {
        if (empty($this->telWork)) {
            return null;
        }

        $tel = $this->getTelWork();

        if ($this->getTelWorkExt()) {
            $tel .= ' ext. ' . $this->getTelWorkExt();
        }

        return $tel;
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
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user = null)
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

    public function getFullname()
    {
        return trim(sprintf('%s %s', $this->getFirstname(), $this->getLastname()));
    }
}

