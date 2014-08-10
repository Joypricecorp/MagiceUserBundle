<?php

namespace Magice\Bundle\AccountBundle\DefaultEntity;

use Doctrine\ORM\Mapping as ORM;
use Magice\Bundle\UserBundle\Model\UserInfo as BaseUserInfo;

/**
 * @ORM\Table(name="mg_user_info")
 * @ORM\Entity
 */
class UserInfo extends BaseUserInfo
{
}