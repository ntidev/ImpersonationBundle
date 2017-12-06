<?php

namespace NTI\ImpersonationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class ImpersonationController extends Controller
{
    /**
     * @Route("/_nti_impersonate/{key}", name="nti_impersonation_impersonate")
     * @param Request $request
     * @param $key
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function impersonateAction(Request $request, $key)
    {
        $em = $this->getDoctrine()->getManager();

        $now = new \DateTime();

        // Look for an existing key
        $impersonationKey = $em->getRepository('NTIImpersonationBundle:ImpersonationKey')->findOneBy(array("key" => $key));

        if(!$impersonationKey) {
            throw new NotFoundHttpException("Unable to find an impersonation request for the provided key.");
        }

        if($now > $impersonationKey->getExpires()) {

            $em->remove($impersonationKey);

            try {
                $em->flush();
            } catch (\Exception $ex) {
                error_log("Unable to remove the impersonation key.");
            }

            throw new NotFoundHttpException("The impersonation request has already expired.");
        }

        $username = $impersonationKey->getUsername();
        $user = $em->getRepository('AppBundle:User\User')->findOneBy(array("username" => $username));

        if(!$user) {
            throw new NotFoundHttpException("Unable to find the user to impersonate.");
        }

        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $tokenStorage = $this->get('security.token_storage');
        $tokenStorage->setToken($token);

        $em->remove($impersonationKey);

        try {
            $em->flush();
        } catch (\Exception $ex) {
            error_log("Unable to remove the impersonation key.");
        }

        $redirectRoute = $this->getParameter('nti_impersonation.redirect_route');

        return $this->redirect($this->generateUrl($redirectRoute));
    }
}
