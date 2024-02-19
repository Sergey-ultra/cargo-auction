<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TruckController extends AbstractController
{
    #[Route('/trucks', name: 'truck.index', methods:['get'])]
    public function index(): Response
    {
        return $this->render('truck/index.html.twig');
    }
    #[Route('/trucks', name: 'truck.create', methods:['get'])]
    public function create(): Response
    {
        return $this->render('truck/index.html.twig');
    }
}
