<?php

namespace App\Service;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MovieService
{

    private MovieRepository $movieRepository;
    private string $posterBasePath;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->posterBasePath = 'https://image.tmdb.org/t/p/original';
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function addMovieFromData($id, $title, $director, $releaseDate, $description, $posterUrl): Movie
    {
        $movie = $this->movieRepository->findOneBy(['tmdbId' => $id]);
        if(is_null($movie)){
            $movie = new Movie();
        }
        $movie->setTmdbId($id);
        $movie->setTitle($title);
        $movie->setDirector($director);
        $movie->setReleaseYear(date('Y', strtotime($releaseDate)));
        $movie->setDescription($description);
        $movie->setPosterUrl($this->posterBasePath . $posterUrl);
        $this->movieRepository->add($movie);

        return $movie;
    }

    public function listMoviesByPage(int $page): Paginator
    {
        return $this->movieRepository->getAllMovies($page);
    }
}