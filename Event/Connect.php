<?php
namespace Magice\Bundle\UserBundle\Event;

use FOS\UserBundle\Model\UserInterface;
use Magice\Bundle\UserBundle\OAuth\Response\ResponseInterface;
use Symfony\Component\EventDispatcher\Event as BaseEvent;
use Symfony\Component\HttpFoundation\Request;

class Connect extends BaseEvent
{
    const ON_CONNECT_NEW = 'magice.listener.user.connect_new_email_notify';

    private $request;
    private $user;
    private $oauthResponse;

    public function __construct(UserInterface $user, ResponseInterface $response, Request $request = null)
    {
        $this->user    = $user;
        $this->request = $request;
        $this->oauthResponse = $response;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function getOAuthResponse()
    {
        return $this->oauthResponse;
    }
}