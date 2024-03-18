<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
class AuthController extends AbstractController
{
    #[Route('/sign-in', name: 'app_sign_in')]
    public function app_sign_in(SerializerInterface $serializer, AuthenticationUtils $authenticationUtils): JsonResponse
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        //$user = $this->getUser()->toArray();

        $user = $this->getUser();


        return $this->json([
            'error' => $error,
            'data' => [
                'userId' => $user->getId(),
                'userEmail' => $user->getEmail(),
            ]
        ]);
    }

    #[Route('/login/{service}', name: 'login-with-service', requirements: ['service' => 'google|facebook'], methods: ['get'])]
    public function login(): JsonResponse
    {
        return $this->json(['data' => ['url' => '']]);
    }

//    #[Route('/sign-in', name: 'app_sign_in')]
//    public function app_sign_in(
//        Request $request,
//        AuthenticationUtils $authenticationUtils,
//        UserPasswordHasherInterface $encoder,
//        AuthenticatorManager $manager,
//        FormLoginAuthenticator $authenticator
//    ): JsonResponse
//    {
//        $payload = $request->getPayload();
//        $email = $payload->get('email');
//        $plainPassword = $payload->get('password');
//
//        $user = new User();
//        $user->setEmail($email);
//        $user->setPassword($encoder->hashPassword($user, $plainPassword));
//
//        $result = $manager->authenticateUser(
//            $user,
//            $authenticator,
//            $request);
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->json(['data' => [
//            'result' => $result,
//            'last_username' => $lastUsername,
//            'error' => $error,
//        ]]);
//    }
}
