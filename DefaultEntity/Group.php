<?php
namespace Magice\Bundle\UserBundle\DefaultEntity;

use Magice\Bundle\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mg_user_group")
 */
class Group extends BaseGroup
{
}