<?php

namespace App\Controller;

use App\Model\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route(
        path: '/movies',
        name: 'app_movie_list',
        methods: ['GET']
    )]
    public function list(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/list.html.twig', [
            'movies' => $movieRepository->listAll(),
        ]);
    }

    #[Route(
        path: '/movies/{slug}',
        name: 'app_movie_details',
        requirements: [
            'slug' => '\d{4}-\w+(-\w+)*'
        ],
        methods: ['GET']
    )]
    public function details(MovieRepository $movieRepository, string $slug): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => $movieRepository->getBySlug($slug),
        ]);
    }
}
