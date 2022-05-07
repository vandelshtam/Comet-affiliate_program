<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;
    
    public function __construct(string $defaultLocale = 'en')
    {
        // if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2) != NULL){
        //     $this->defaultLocale = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
        // }
        // else{
        //     $this->defaultLocale = $defaultLocale;
        // }
        $this->defaultLocale = $defaultLocale;
    }


    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
    
        if (!$request->hasPreviousSession()) {
            return;
        }
      
    //    if($locale = $request->attributes->get('_locale') == NULL){
    //        //dd($request->getSession('_locale_current'));
    //         $locale = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
    //        // $request->attributes->set('_locale', $locale);
    //     }
        // else{
        //     //$locale = $request->attributes->get('_locale');
        //     //dd($locale);
        //      $request->attributes->set('_locale', $locale);
        // } 
       
        //try to see if the locale has been set as a _locale routing parameter
        // if ($locale = $request->attributes->get('_locale')) {
        //     $request->getSession()->set('_locale', $locale);
        //     $request->setLocale($request->getSession()->get('_locale', $locale));
        // }
        $locale = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
        //$request->setLocale('');
        //$locale = "";
        if ($locale != NULL) {
            $request->setLocale($request->getSession()->get('_locale', $locale));
            //$request->setLocale($locale);
            //dd($locale);
            //$request->getSession()->set('_locale', $locale);
        }
         else {
            // if no explicit locale has been set on this request, use one from the session
            //dd($request);
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
        //dd($request);
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}