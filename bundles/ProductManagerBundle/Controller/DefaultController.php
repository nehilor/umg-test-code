<?php

namespace ProductManagerBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{
    /**
     * @Route("/product_manager")
     */
    public function indexAction(Request $request)
    {
        return new Response('Hello world from product_manager');
    }
}
