<?php
namespace Magice\Bundle\UserBundle\OAuth\Response;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

interface ResponseInterface extends UserResponseInterface
{
    public function getId();

    public function getProvider();

    /**
     * @return \DateTime|null
     */
    public function getBirthday();

    public function getLocale();

    public function getLocation();

    public function getFirstName();

    public function getLastName();

    public function getProfile();

    public function getGender();

    /**
     * @param bool $datetime
     *
     * @return null|string|\DateTime
     */
    public function getExpiresIn($datetime = false);
}