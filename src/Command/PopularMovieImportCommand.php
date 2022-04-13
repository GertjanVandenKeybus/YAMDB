<?php

namespace App\Command;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\MovieService;
use App\Service\TMDBApiWrapper;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:import-popular-movies',
    description: 'Import the first 500 pages of popular movies from TMDB',
    aliases: ['app:import-pmov'],
    hidden: false
)]
class PopularMovieImportCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import-popular-movies';
    private TMDBApiWrapper $TMDBApiWrapper;
    private MovieService $movieService;

    public function __construct(TMDBApiWrapper $TMDBApiWrapper, MovieService $movieService)
    {
        $this->TMDBApiWrapper = $TMDBApiWrapper;
        $this->movieService = $movieService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('pages', InputArgument::REQUIRED, 'The amount of pages to import (max 500).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pages = $input->getArgument('pages');

        if ($pages < 0 || $pages > 500) {
            $output->writeln([
                'Pages argument invalid. Please use an integer from 1 to 500.'
            ]);
            return -1;
        }

        $output->writeln([
            '============',
            "Importing first $pages pages of popular movies from TMDB...",
            '============',
        ]);

        $progressBar = new ProgressBar($output, $pages);
        $progressBar->start();
        $counter = 0;

        for ($i = 1; $i <= $pages; $i++) {
            try {
                $popularMoviesList = $this->TMDBApiWrapper->get('https://api.themoviedb.org/3/movie/popular', ['page' => $i, 'language' => 'en-US']);
                foreach ($popularMoviesList['results'] as $movie) {
                    $id = $movie['id'];
                    $movieCredits = $this->TMDBApiWrapper->get("https://api.themoviedb.org/3/movie/$id/credits", []);
                    $director = $this->getDirectorFromCredits($movieCredits);
                    $this->movieService->addMovieFromData($id, $movie['title'], $director, $movie['release_date'], $movie['overview']);
                    $counter++;
                }
            } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface | OptimisticLockException | ORMException $e) {
                $output->writeln([
                    $e->getMessage()
                ]);
                return -1;
            }
            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln([
            '',
            '============',
            "Finished importing $counter movies from TMDB!",
            '============',
        ]);
        return Command::SUCCESS;
    }

    private function getDirectorFromCredits(array $movieCredits): string
    {
        foreach ($movieCredits['crew'] as $crewMember) {
            if ($crewMember['job'] === 'Director') {
                return $crewMember['name'];
            }
        }

        return '';
    }
}