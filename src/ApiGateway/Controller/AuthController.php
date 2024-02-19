<?php

namespace App\ApiGateway\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/sign-out', name: 'app_sign_out')]
    public function app_sign_out(): Response
    {
        $response = $this->redirectToRoute('cargo.index', [], Response::HTTP_SEE_OTHER);
        $response->headers->removeCookie('jwt');

        return $response;
        // controller can be blank: it will never be called!
//        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
