<?php

declare(strict_types=1);

namespace App\ApiGateway\Listeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements EventSubscriberInterface
{
    public function __construct(private readonly string $defaultLocale = 'en')
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
//        if ($locale = $request->attributes->get('_locale')) {
//            //$request->getSession()->set('_locale', $locale);
//        } else {

            $request->setLocale($request->cookies->get('locale', $this->defaultLocale));
       // }
    }


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 20]
            ],
        ];
    }
}