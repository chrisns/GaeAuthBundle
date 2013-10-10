<?php

namespace chrisns\GaeAuthBundle\Handler;

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

require_once 'google/appengine/api/users/User.php';
require_once 'google/appengine/api/users/UserService.php';

class AuthenticationHandler implements LogoutSuccessHandlerInterface
{
    public function onLogoutSuccess(Request $request)
    {
        return new RedirectResponse(UserService::createLogoutUrl("/"));
    }
}
