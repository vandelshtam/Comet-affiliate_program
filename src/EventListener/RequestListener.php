<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        // ...
    }
}