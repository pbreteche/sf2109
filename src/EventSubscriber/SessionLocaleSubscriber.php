<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SessionLocaleSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $sessionLocale = $request->getSession()->get('locale');

        if ($sessionLocale) {
            $request->setLocale($sessionLocale);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // la priorité doit être au dessus de 16 pour passer avant
            // le LocalListener qui fournit la locale au service de traduction
            // et en dessous de 24 pour après notre service qui détecte la locale
            // à partir du navigateur
            'kernel.request' => ['onKernelRequest', 23],
        ];
    }
}
