<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as BaseAuthenticationSuccessHandler;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private AuthenticationSuccessHandlerInterface $baseHandler;

    public function __construct(BaseAuthenticationSuccessHandler $baseHandler)
    {
        $this->baseHandler = $baseHandler;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
//        if (!$token->getUser()->isVerified()) {
//            return new JsonResponse(['is_required_email_verification' => true]);
//        }

        return $this->baseHandler->onAuthenticationSuccess($request, $token);
    }
}
