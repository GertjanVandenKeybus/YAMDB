<?php

namespace App\Command;

use App\Service\TMDBApiWrapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;

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

    public function __construct(TMDBApiWrapper $TMDBApiWrapper)
    {
        $this->TMDBApiWrapper = $TMDBApiWrapper;

        parent::__construct();
    }

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            '============',
            'Importing first 500 pages of popular movies from TMDB...',
            '============',
        ]);

        $progressBar = new ProgressBar($output, 500);
        $progressBar->start();
        $counter = 0;

        for ($i = 1; $i <= 500; $i++) {
            $popularMoviesList = $this->TMDBApiWrapper->get('https://api.themoviedb.org/3/movie/popular', ['page' => $i, 'language' => 'en-US']);
            $counter += count($popularMoviesList['results']);
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
}