<?php
namespace Magice\Bundle\UserBundle\Entity;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManger;

class UserManager extends BaseUserManger implements UserConnectManagerInterface
{
    /**
     * @param string $provider
     * @param string $username
     *
     * @return User|null
     */
    public function findUserConnectByUsername($provider, $username)
    {
        if ($user = $this->findUserConnectByCriteria(array('provider' => $provider, 'username' => $username))) {
            return $user;
        } else {
            return null;
        }
    }
    /**
     * @param string $provider
     * @param string $email
     *
     * @return User|null
     */
    public function findUserConnectByEmail($provider, $email)
    {
        if ($user = $this->findUserConnectByCriteria(array('provider' => $provider, 'email' => $email))) {
            return $user;
        } else {
            return null;
        }
    }

    /**
     * @param array $criteria
     *
     * @return User|null
     */
    public function findUserConnectByCriteria(array $criteria)
    {
        /**
         * @var UserConnect $connect
         */
        if ($connect = $this->objectManager
            ->getRepository(self::getUserConnectClass())
            ->findOneBy($criteria)
        ) {
            return $connect->getUser();
        } else {
            return null;
        }
    }

    public static function getUserConnectClass()
    {
        return 'Magice\Bundle\UserBundle\Entity\UserConnect';
    }
}