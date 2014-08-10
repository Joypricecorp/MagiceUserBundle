<?php
namespace Magice\Bundle\UserBundle\Model;

interface UserConnectManagerInterface
{
    /**
     * @param string $provider
     * @param string $username
     *
     * @return null|User
     */
    public function findUserConnectByUsername($provider, $username);

    /**
     * @param $provider
     * @param $email
     *
     * @return null|User
     */
    public function findUserConnectByEmail($provider, $email);

    /**
     * @param array $criteria
     *
     * @return null|User
     */
    public function findUserConnectByCriteria(array $criteria);

    /**
     * user connect class
     * @return string
     */
    public static function getUserConnectClass();
}