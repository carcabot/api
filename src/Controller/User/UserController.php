<?php

namespace App\Controller\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Manager\User\UserManager;

class UserController extends AbstractController
{
    /**
     * @Route("/api/me", name="get_my_infos", methods={"GET"})
     */
    public function getMyInfos()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return new JsonResponse($this->getUser()); 
    }
    
    /**
     * @Route("/api/users", name="create_user", methods={"POST"})
     */
    public function register(Request $request, UserManager $userManager, AuthenticationSuccessHandler $authenticationSuccessHandler, JWTTokenManagerInterface $jwtManager)
    {
        $user = $userManager->create(
            $request->request->get('username'),
            $request->request->get('email'),
            $request->request->get('password')
        );
        return $authenticationSuccessHandler->handleAuthenticationSuccess($user, $jwtManager->create($user));
    }
}