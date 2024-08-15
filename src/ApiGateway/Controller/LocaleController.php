<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/locale/{locale}', name: 'loc', requirements: ['locale' => 'en|ru'], methods:['get'])]
    public function setLocale(Request $request, string $locale): RedirectResponse
    {
        $cookie = new Cookie( 'locale', $locale);
        $request->headers->setCookie($cookie);


        return $this->redirect($request->headers->get('referer'));
    }
}