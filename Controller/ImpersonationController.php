<?php

namespace NTI\ImpersonationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ImpersonationController
 * @package NTI\ImpersonationBundle\Controller
 * @Route("/nti/impersonate")
 */
class ImpersonationController extends Controller
{
    /**
     * @Route("/user/{key}", name="nti_impersonation_impersonate")
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

        $userClass = $this->container->getParameter('nti_impersonation.user_class');
        $userClassProperty = $this->container->getParameter('nti_impersonation.user_class_property');
        $firewall = $this->container->getParameter('nti_impersonation.firewall');

        $username = $impersonationKey->getUsername();
        $user = $em->getRepository($userClass)->findOneBy(array($userClassProperty => $username));

        if(!$user) {
            throw new NotFoundHttpException("Unable to find the User to impersonate.");
        }

        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
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

    /**
     * @Route("/generate/{username}", name="nti_generate_key_impersonate")
     * @param Request $request
     * @param $username
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function generateKey(Request $request, $username){
        $key = '';

        try {
            $key = $this->get('nti_impersonation.key_generator')->generateKeyServices($username);
        } catch (\Exception $ex) {
            $key = '';
            error_log("Unable to generate the impersonation key.");
        }

        return new JsonResponse(
            [
                "data"=>["key"=>$key],
                "code"=>200
            ]
        );
    }

}
