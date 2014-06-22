<?php

namespace Magice\Bundle\UserBundle\EventListener;

use Magice\Bundle\UserBundle\Event\Connect;
use Magice\Bundle\UserBundle\Mailer\TwigSwift;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConnectNewEmailNotify implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(TwigSwift $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Connect::ON_CONNECT_NEW => 'onNewConnected',
        );
    }

    public function onNewConnected(Connect $event)
    {
        $this->mailer->sendNewConnectedEmailMessage($event->getUser(), $event->getOAuthResponse());
    }
}
