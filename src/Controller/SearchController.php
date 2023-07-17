<?php

namespace App\Controller;

use App\Model\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly MovieRepository $movieRepository,
    )
    {
    }

    public function main(): Response
    {
        return $this->render('search/search.html.twig', []);
    }


    #[Route(
        path: '/search',
        name: 'search',
        methods: ['GET']
    )]
    public function search(Request $request)
    {
        $search = $request->query->get('q');

        $movies = Movie::fromEntities(
            $this->movieRepository->findByTitle($search));
        //dd($movies);
   

        return $this->render('search/result.html.twig', [
                'movies' => $movies,   
                'search' => $search ,       
        ]);

    }
}
