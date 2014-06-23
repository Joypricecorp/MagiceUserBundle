<?php

namespace Magice\Bundle\UserBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AlreadyLogedInRedirect implements EventSubscriberInterface
{
    private $router;
    private $targetRoutes;

    public function __construct(UrlGeneratorInterface $router, $targetRoutes = array())
    {
        $this->router      = $router;
        $this->targetRoutes = $targetRoutes;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        var_dump($event);exit;
    }
}