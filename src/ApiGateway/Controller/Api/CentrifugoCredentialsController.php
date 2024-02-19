<?php
declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use Fresh\CentrifugoBundle\Service\Credentials\CredentialsGenerator;
use Fresh\CentrifugoBundle\User\CentrifugoUserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CentrifugoCredentialsController extends ApiController
{
    #[Route(path: '/centrifugo/credentials/user', name: 'get_centrifugo_credentials_for_current_user', methods: [Request::METHOD_GET])]
    public function getJwtTokenForCurrentUserAction(CredentialsGenerator $credentialsGenerator): JsonResponse
    {
        /** @var CentrifugoUserInterface $user */
        $user = $this->getUser();
        $token = null;

        if ($user) {
            $token = $credentialsGenerator->generateJwtTokenForUser($user);
        }

        return $this->json(['data' => ['token' => $token]]);
    }
}
