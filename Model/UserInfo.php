<?php

namespace Magice\Bundle\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Magice\Bundle\UserBundle\Entity\UserInfoAbstract;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="mg_user_info")
 * @ORM\Entity
 */
class UserInfo extends UserInfoAbstract
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}