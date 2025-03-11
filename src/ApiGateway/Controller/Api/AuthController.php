<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\VerificationDTO;
use App\Modules\User\Application\Security\EmailVerifier;
use App\Modules\User\Infrastructure\Api\UserApi;
use App\Modules\User\Infrastructure\DTO\UserPayloadDTO;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


//#[Route('/api', name: 'api_')]
class AuthController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/api/register', name: 'api.register', methods: ['post'])]
    public function register(#[MapRequestPayload('json')]UserPayloadDTO $payload, UserApi $userApi): JsonResponse
    {
        try {
            $user = $userApi->save($payload);
        } catch (UniqueConstraintViolationException $exception) {
            return $this->json(['data' => ['status' => 'email_is_already_exists']]);
        }
        $this->sendVerificationEmail($user);

        return $this->json(['data' => ['status' => 'is_required_email_verification']]);
    }

    #[Route('/api/send-verification-email', name: 'api.send-verification-email', methods: ['post'])]
    public function requestVerificationEmail(#[MapRequestPayload]VerificationDTO $payload,  UserApi $userApi): JsonResponse
    {
        $user = $userApi->getUserByEmail($payload->email);
        if (!$user) {
            return $this->json(['data' => ['status' => 'fail']], Response::HTTP_NOT_FOUND);
        }

        $this->sendVerificationEmail($user);

        return $this->json(['data' => ['status' => 'ok']]);
    }

    private function sendVerificationEmail(UserInterface $user): void
    {
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('noreply@cargo-auction.ru', 'Cargo-Auction.ru'))
                ->to($user->getEmail())
                ->subject('Продолжение регистрации')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

//    #[Route('/sign-in', name: 'app_sign_in')]
//    public function app_sign_in(SerializerInterface $serializer, AuthenticationUtils $authenticationUtils): JsonResponse
//    {
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//        //$user = $this->getUser()->toArray();
//
//        $user = $this->getUser();
//
//
//        return $this->json([
//            'error' => $error,
//            'data' => [
//                'userId' => $user->getId(),
//                'userEmail' => $user->getEmail(),
//            ]
//        ]);
//    }

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
