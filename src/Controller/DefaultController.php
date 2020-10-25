<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class DefaultController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        //return new Response('<html><body>Hello world</body></html>');
        return $this->render('index.html.twig');
    }
}