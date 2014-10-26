<?php
namespace Magice\Bundle\UserBundle\OAuth\Response;

use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

class Vkontakte extends PathUserResponse implements ResponseInterface
{
    protected $paths = array(
        'email'               => 'email',
        'username'            => 'email',
        'profilepicture'      => 'response.0.photo_50',
        'access_token'        => 'access_token',
        'access_token_expire' => 'access_token_expire',
        'profile'             => 'response.0.screen_name',
        'locale'              => 'locale',
        'location'            => 'response.0.country', // location.name
        'gender'              => 'response.0.sex',
        'first_name'          => 'response.0.first_name',
        'last_name'           => 'response.0.last_name',
        'birthday'            => 'response.0.bdate',
    );

    public function getProvider()
    {
        return 'vkontakte';
    }

    public function getId()
    {
        return $this->getValueForPath('identifier');
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getValueForPath('username');
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthday()
    {
        if ($birthday = $this->getValueForPath('birthday')) {
            $birthday = str_replace('.', '-', $birthday);
            return new \DateTime(date('Y-m-d', strtotime($birthday)));
        } else {
            return null;
        }
    }

    public function getLocale()
    {
        return $this->getValueForPath('locale');
    }

    public function getLocation()
    {
        if ($location = $this->getValueForPath('location')) {
            if (is_array($location) && !empty($location['name'])) {
                return $location['name'];
            } else {
                return $location;
            }
        }

        return null;
    }

    public function getLastName()
    {
        return $this->getValueForPath('last_name');
    }

    public function getFirstName()
    {
        return $this->getValueForPath('first_name');
    }

    public function getProfile()
    {
        return 'https://vk.com/'.$this->getValueForPath('profile');
    }

    public function getGender()
    {
        if ($gender = $this->getValueForPath('gender')) {
            if ($gender === 2 || $gender === 0) return 'M';
            if ($gender === 1) return 'F';
        } else {
            return 'M';
        }
    }

    public function getAccessToken()
    {
        if ($token = $this->getValueForPath('access_token')) {
            return $token;
        } else {
            return parent::getAccessToken();
        }
    }

    public function getExpiresIn($dateTime = false)
    {
        if ($token = $this->getValueForPath('access_token_expire')) {
            // format 'c' = \DateTime::ISO8601 (opauth)
            $date = \DateTime::createFromFormat(\DateTime::ISO8601, $token);
            $time = $date->getTimestamp() + $date->getOffset();
        } else {
            $time = parent::getExpiresIn();
        }

        if ($dateTime) {
            return (new \DateTime())->setTimestamp($time);
        } else {
            return $time;
        }
    }

    public function toArray()
    {
        $array = array();
        foreach ($this->paths as $key => $val) {
            $array[$key] = $this->getValueForPath($key);
        }

        $array['access_token_expire'] = $this->getExpiresIn();
        $array['birthday']            = $this->getBirthday();
        $array['location']            = $this->getLocation();
        $array['gender']              = $this->getGender();

        return $array;
    }
}
