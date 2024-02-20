<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransportController extends AbstractController
{
    #[Route('/transport', name: 'transport.index', methods:['get'])]
    public function index(): Response
    {
        return $this->render('transport/index.html.twig');
    }
    #[Route('/transport/create', name: 'transport.create', methods:['get'])]
    public function create(): Response
    {
        return $this->render('transport/form.html.twig', [
            'errors' => [],
        ]);
    }
}
