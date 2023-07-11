<?php

namespace App\Controller;

use App\Model\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavBarController extends AbstractController
{
    public function main(MovieRepository $movieRepository): Response
    {
        $movies = Movie::fromEntities($movieRepository->listAll());

        return $this->render('_navbar.html.twig', [
            'movies' => $movies,
        ]);
    }
}
