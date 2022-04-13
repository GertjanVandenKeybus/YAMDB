<?php

namespace App\Service;

use App\Entity\Movie;
use App\Repository\MovieRepository;

class MovieService
{

    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function addMovieFromData($id, $title, $director, $releaseDate, $description): Movie
    {
        $movie = $this->movieRepository->findOneBy(['tmdb_id' => $id]);
        if(is_null($movie)){
            $movie = new Movie();
        }
        $movie->setTmdbId($id);
        $movie->setTitle($title);
        $movie->setDirector($director);
        $movie->setReleaseYear(date('Y', strtotime($releaseDate)));
        $movie->setDescription($description);
        $this->movieRepository->add($movie);

        return $movie;
    }
}