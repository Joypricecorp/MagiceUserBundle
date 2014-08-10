<?php
namespace Magice\Bundle\UserBundle\DefaultEntity;

use Magice\Bundle\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Magice\Bundle\UserBundle\Model\UserRepository")
 * @ORM\Table(name="mg_user")
 */
class User extends BaseUser
{
}