<?php

namespace App\Controller;

use App\Service\TMDBApiWrapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private TMDBApiWrapper $TMDBApiWrapper;

    public function __construct(TMDBApiWrapper $TMDBApiWrapper)
    {
        $this->TMDBApiWrapper = $TMDBApiWrapper;
    }

    #[Route('/movies/popular')]
    public function getPopular(Request $request): Response
    {
        $counter=0;
        for ($i = 1; $i <= 5; $i++) {
            $popularMoviesList = $this->TMDBApiWrapper->get('https://api.themoviedb.org/3/movie/popular', ['page' => $i, 'language' => 'en-US']);
            $counter+=count($popularMoviesList['results']);
        }
        $response = new Response();
        $response->setContent(json_encode($counter));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}