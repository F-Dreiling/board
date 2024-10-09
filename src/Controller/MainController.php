<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        return new Response(content: '<h1>Welcome</h1>');
    }

    #[Route('/custom/{name?}', name: 'custom')]
    public function custom(Request $req)
    {
        dump($req);
        return new Response(content: '<h1>Welcome mate</h1>');
    }
}
