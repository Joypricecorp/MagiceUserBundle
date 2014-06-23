<?php

namespace Magice\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationSuccess implements EventSubscriberInterface
{
    private $router;
    private $targetRoute;

    public function __construct(UrlGeneratorInterface $router, $targetRoute)
    {
        $this->router      = $router;
        $this->targetRoute = $targetRoute;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegisterSuccess',
        );
    }

    public function onRegisterSuccess(FormEvent $event)
    {
        $url = $this->router->generate($this->targetRoute ? : 'fos_user_register_confirmed');

        $event->setResponse(new RedirectResponse($url));
    }
}