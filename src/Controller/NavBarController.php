<?php

namespace App\Controller;

use App\Model\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavBarController extends AbstractController
{
    public function main(MovieRepository $movieRepository): Response
    {
        return $this->render('_navbar.html.twig', [
            'movies' => $movieRepository->listAll(),
        ]);
    }
}
