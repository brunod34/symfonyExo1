<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavBarController extends AbstractController
{
    public function __construct(
        private readonly MovieRepository $movieRepository,
    ) {
    }

    public function main(): Response
    {
        return $this->render('_navbar.html.twig', [
            'movies' => $this->movieRepository->listBySlugAndTitle(),
        ]);
    }
}
