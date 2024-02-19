<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolicyController extends AbstractController
{
    #[Route('policies/agreement/', name: 'agreement')]
    public function agreement(): Response
    {
        return $this->render('policy/agreement.html.twig');
    }
}
