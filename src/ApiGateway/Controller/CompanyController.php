<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/company/{id}', name: 'company.show', requirements: ['id' => '\d+'], methods: ['get'])]
    public function show(): Response
    {
        return $this->render('company/show.html.twig');
    }
}
