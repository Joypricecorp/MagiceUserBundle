<?php
namespace Magice\Bundle\UserBundle\OAuth\Response;

use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

class Facebook extends PathUserResponse implements ResponseInterface
{
    protected $paths = array(
        'identifier'          => 'id',
        'nickname'            => 'username',
        'realname'            => 'name',
        'email'               => 'email',
        'username'            => 'username',
        'profilepicture'      => null,
        'access_token'        => 'access_token',
        'access_token_expire' => 'access_token_expire',
        'profile'             => 'link',
        'locale'              => 'locale',
        'location'            => 'location', // location.name
        'gender'              => 'gender',
        'first_name'          => 'first_name',
        'last_name'           => 'last_name',
        'birthday'            => 'birthday',
    );

    public function getProvider()
    {
        return 'facebook';
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
        return $this->getValueForPath('profile');
    }

    public function getGender()
    {
        if ($gender = $this->getValueForPath('gender')) {
            // M,F
            return strtoupper($gender[0]);
        } else {
            return 'M';
        }
    }

    public function getProfilePicture()
    {
        return sprintf('https://graph.facebook.com/%s/picture?type=square', $this->getId());
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