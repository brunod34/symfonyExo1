<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route(
        path: '/hello/{name}',
        name: 'app_hello',
        requirements: [
            'name' => '[a-zA-Z]+(-[a-zA-Z]+)*'
        ],
        defaults: [
            'name' => 'Adrien',
        ],
    )]
    public function index(string $name): Response
    {
        return new Response(<<<"HTML"
        <body>Hello {$name}</body>
        HTML
        );
    }
}
