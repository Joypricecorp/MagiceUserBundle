<?php
namespace Magice\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class SecurityController extends BaseSecurityController
{
    public function loginAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if ($target = $this->container->getParameter('magice.user.already_logedin_redirect_target')) {
                $url = $this->container->get('router')->generate($target);
            } else {
                $url = $request->getBaseUrl();
            }

            return new RedirectResponse($url);
        }

        $result = parent::loginAction($request);

        # TODO: if ($result['error']) parse to prevent sensitive error message
        return $result;
    }
}