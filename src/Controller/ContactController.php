<?php

namespace App\Controller;

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route(
     *     "/{_locale}/contact",
     *     name="contact",
     *     requirements={
     *         "_locale": "en|ru|ua|fr|de",
     *     }
     * )
     */
    public function contact()
    {
    }
}
