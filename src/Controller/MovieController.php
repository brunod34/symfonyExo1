<?php

namespace App\Controller;

use App\Entity\Movie as MovieEntity;
use App\Form\MovieType;
use App\Model\Movie;
use App\Omdb\Client\ApiClientInterface;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    public function __construct(
        private readonly MovieRepository $movieRepository,
        private readonly ApiClientInterface $omdbApiClient,
    ) {
    }

    #[Route(
        path: '/movies',
        name: 'app_movie_list',
        methods: ['GET']
    )]
    public function list(): Response
    {
        $movies = Movie::fromEntities($this->movieRepository->listAll());

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route(
        path: '/movies/{slug}',
        name: 'app_movie_details',
        requirements: [
            'slug' => MovieEntity::SLUG_FORMAT,
        ],
        methods: ['GET']
    )]
    public function detailsFromDatabase(string $slug): Response
    {
        $movie = Movie::fromEntity($this->movieRepository->getBySlug($slug));

        $this->denyAccessUnlessGranted(MovieVoter::VIEW_DETAILS, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route(
        path: '/movies/{imdbID}',
        name: 'app_movie_details_omdb',
        requirements: [
            'imdbID' => 'tt.+',
        ],
        methods: ['GET']
    )]
    public function detailsFromOmdb(string $imdbID): Response
    {
        $omdbMovieModel = $this->omdbApiClient->getById($imdbID);
        $movie = Movie::fromOmdbModel($omdbMovieModel);

        $this->denyAccessUnlessGranted(MovieVoter::VIEW_DETAILS, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route(
        path: '/admin/movies/new',
        name: 'app_movie_new',
        methods: ['GET', 'POST']
    )]
    #[Route(
        path: '/admin/movies/{slug}/edit',
        name: 'app_movie_edit',
        requirements: [
            'slug' => MovieEntity::SLUG_FORMAT,
        ],
        methods: ['GET', 'POST']
    )]
    public function newOrEdit(Request $request, string|null $slug = null): Response
    {
        $movieEntity = new MovieEntity();
        if (null !== $slug) {
            $movieEntity = $this->movieRepository->getBySlug($slug);
        }

        $movieForm = $this->createForm(MovieType::class, $movieEntity);
        $movieForm->handleRequest($request);

        if ($movieForm->isSubmitted() && $movieForm->isValid()) {
            $this->movieRepository->save($movieEntity, true);

            return $this->redirectToRoute('app_movie_details', ['slug' => $movieEntity->getSlug()]);
        }

        return $this->render('movie/new_or_edit.html.twig', [
            'movie_form' => $movieForm,
            'editing_movie' => null !== $slug ? $movieEntity : null,
        ]);
    }
}
