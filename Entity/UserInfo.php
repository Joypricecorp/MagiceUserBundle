<?php

namespace Magice\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Magice\Orm\Doctrine\Types\Phone\Phone;
use JMS\Serializer\Annotation as JMS;
// Now PhoneNumber have no Interface
use libphonenumber\PhoneNumber;

/**
 * @ORM\Table(name="fos_user_info")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @JMS\ExclusionPolicy("NONE")
 */
class Info
{
    const YEAR_BC_DIFF = 543;
    const YEAR_MIN_AGE = 13;
    const YEAR_MAG_AGE = 60;

    use TimestampableEntity;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="inf_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="inf_gender", type="string", length=1)
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(name="inf_firstname", type="string", length=50)
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="inf_lastname", type="string", length=50)
     */
    private $lastname;

    /**
     * @ORM\Column(name="inf_display_name", type="string", length=50, nullable=true)
     */
    private $displayName;

    /**
     * @ORM\Column(name="inf_avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     * @ORM\Column(name="inf_personal_id", type="string", length=13, nullable=true)
     */
    private $personalId;

    /**
     * @var \DateTime
     * @ORM\Column(name="inf_birth_day", type="date", nullable=true)
     */
    private $birthDay;

    /**
     * @var Phone
     * @ORM\Column(name="inf_mobile", type="phone", nullable=true)
     */
    private $mobile;

    /**
     * @var Phone
     * @ORM\Column(name="inf_tel_home", type="phone", nullable=true)
     */
    private $telHome;

    /**
     * @var Phone
     * @ORM\Column(name="inf_tel_work", type="phone", nullable=true)
     */
    private $telWork;

    /**
     * @var string
     * @ORM\Column(name="inf_tel_work_ext", type="string", length=5, nullable=true)
     */
    private $telWorkExt;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="Magice\Bundle\UserBundle\Entity\User", inversedBy="info")
     * @ORM\JoinColumn(name="inf_user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $user;

    /**
     * @var string
     * @JMS\Accessor(getter="getFullname")
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
        $this->firstname = $firstname;

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
        $this->lastname = $lastname;

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
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
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
        $this->personalId = $personalId;

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
     * Set birthDay
     *
     * @param \DateTime $birthDay
     *
     * @return $this
     */
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * Get BirthDay
     * @return \DateTime
     */
    public function getBirthDay()
    {
        return $this->birthDay;
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
     * @return Phone
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
     * @return Phone
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
     * @return Phone
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
        return sprintf('%s %s', $this->getFirstname(), $this->getLastname());
    }
}

