<?php

namespace Magice\Bundle\UserBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AlreadyLogedInRedirect implements EventSubscriberInterface
{
    private $security;
    private $router;
    private $routes;
    private $target;

    public function __construct(SecurityContextInterface $security, Router $router, $target = '', $routes = array())
    {
        $this->security = $security;
        $this->router   = $router;
        $this->target   = $target;
        $this->routes   = $routes;
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
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if (empty($this->routes) || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        if (!$this->security->getToken() || !$this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return;
        }

        $pathInfo = $this->router->getContext()->getPathInfo();
        $match    = $this->router->getMatcher()->match($pathInfo);

        if (!empty($match['_route'])) {

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

            if (in_array($match['_route'], $this->routes)) {
                $event->setResponse(new RedirectResponse($url));
            } else {
                foreach ($this->routes as $path) {
                    if ($path === $pathInfo) {
                        $event->setResponse(new RedirectResponse($url));
                    }
                }
            }
        }
    }
}