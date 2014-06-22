<?php
namespace Magice\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Magice\Bundle\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="mg_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Magice\Bundle\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="mg_user_group_map",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Magice\Bundle\UserBundle\Entity\Connect", mappedBy="user", cascade={"persist"})
     */
    protected $connects;

    /**
     * @ORM\OneToOne(targetEntity="Magice\Bundle\UserBundle\Entity\User\Info", mappedBy="user", cascade={"persist"})
     */
    protected $info;

    /**
     * user for serializer
     */
    private $avatar;

    public function __construct()
    {
        parent::__construct();

        $this->connects = new ArrayCollection();
        //$this->address  = new ArrayCollection();
        //$this->bankings = new ArrayCollection();
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string  $provider
     * @param UserConnect $connect
     *
     * @return $this
     */
    public function setConnect($provider, UserConnect $connect)
    {
        $this->connects->set($provider, $connect);

        return $this;
    }

    /**
     * @param $provider
     *
     * @return UserConnect|null
     */
    public function getConnect($provider)
    {
        return $this->getConnects()->get($provider);
    }

    /**
     * @return ArrayCollection
     */
    public function getConnects()
    {
        $connects = new ArrayCollection();

        /**
         * @var UserConnect $c
         */
        foreach ($this->connects as $c) {
            $connects->set($c->getProvider(), $c);
        }

        return $connects;
    }

    /**
     * @param $provider
     *
     * @return $this
     */
    public function removeConnect($provider)
    {
        if ($this->getConnects()->containsKey($provider)) {
            $this->getConnects()->remove($provider);
        }

        return $this;
    }

    /**
     * @param Info $info
     *
     * @return $this
     */
    public function setInfo(Info $info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return Info
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getDisplayNameShowing()
    {
        if ($info = $this->getInfo()) {
            return $info->getDisplayName();
        }

        return $this->getUsername();
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        $info = $this->getInfo();

        return $this->avatar = $info
            ? $info->getAvatar()
            : 'https://cdn.joyprice.com/img/logo/jp-logo-back-200x200.png';
    }
}