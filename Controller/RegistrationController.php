<?php
namespace Magice\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class RegistrationController extends BaseRegistrationController
{
    public function registerAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $url = $request->getBaseUrl();
            if ($target = $this->container->getParameter('magice.user.already_logedin_redirect_target')) {
                $url = $request->getBaseUrl() . $target;
            }

            return new RedirectResponse($url);
        }

        return parent::registerAction($request);
    }
}