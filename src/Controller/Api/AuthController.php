<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManager;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class AuthController extends AbstractController
{
    #[Route('/sign-in', name: 'app_sign_in')]
    public function app_sign_in(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        UserPasswordHasherInterface $encoder,
        AuthenticatorManager $manager,
        FormLoginAuthenticator $authenticator
    ): JsonResponse
    {
        $payload = $request->getPayload();
        $email = $payload->get('email');
        $plainPassword = $payload->get('password');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($encoder->hashPassword($user, $plainPassword));

        $result =  $manager->authenticateUser(
            $user,
            $authenticator,
            $request);
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->json(['data' => [
            'result' => $result,
            'last_username' => $lastUsername,
            'error' => $error,
        ]]);
    }
}
