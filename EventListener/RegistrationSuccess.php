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
    private $target;

    public function __construct(UrlGeneratorInterface $router, $target = '')
    {
        $this->router = $router;
        $this->target = $target;
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
        // route or url
        if (empty($this->target)) {
            $url = $event->getRequest()->getBaseUrl();
        } else {
            if (preg_match('/\//', $this->target)) {
                $url = $this->target;
            } else {
                $url = $this->router->generate($this->target);
            }
        }

        $event->setResponse(new RedirectResponse($url));
    }
}