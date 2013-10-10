<?php
namespace chrisns\GaeAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

require_once 'google/appengine/api/users/User.php';
require_once 'google/appengine/api/users/UserService.php';

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $gaeUser = UserService::getCurrentUser();
        // destination is either destination parameter on url, the referer or site root
        $destination = $request->get('destination') ? $request->get('destination') : ( $request->headers->get('referer') ?  $request->headers->get('referer') : "/");
        $localUser = $this->get('security.context')->getToken()->getUser();

        if ($localUser == "anon.") {
            // user is not logged in
            if ($gaeUser) {
                // user is logged in via gae
                $localUser = $userManager->findUserByUsername($gaeUser->getEmail());
                if (!$localUser) {
                    // user doesn't exist, so create it
                    $localUser = $userManager->createUser();
                    $localUser->setUsername($gaeUser->getEmail());
                    $localUser->setEmail($gaeUser->getEmail());
                    $localUser->setPassword('nopassword');
                    $userManager->updateUser($localUser);
                }
                $providerKey = $this->container->getParameter('fos_user.firewall_name');
                $token = new UsernamePasswordToken($localUser, null, $providerKey, $localUser->getRoles());
                $this->container->get('security.context')->setToken($token);

                return $this->redirect($destination);
            } else {
                // user is not logged in via gae
                return $this->redirect(UserService::createLoginUrl("/login?destination={$destination}"));
            }
        }

        return $this->redirect($destination);
    }
}
