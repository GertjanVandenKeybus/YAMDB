<?php

namespace App\Controller;

use App\Service\MovieService;
use App\Service\TMDBApiWrapper;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private TMDBApiWrapper $TMDBApiWrapper;
    private MovieService $movieService;

    public function __construct(TMDBApiWrapper $TMDBApiWrapper, MovieService $movieService)
    {
        $this->TMDBApiWrapper = $TMDBApiWrapper;
        $this->movieService = $movieService;
    }

    #[Route('/movies/popular/{page}', name: 'movies_popular')]
    public function getPopularByPage(Request $request, $page = 1): Response
    {
        $movies = $this->movieService->listMoviesByPage($page);

        return $this->render('movie/list.html.twig', [
            'movies' => $movies,
            'page' => $page,
            'maxPages' => count($movies)/5
        ]);
    }

    #[Route('/movies/import/{pages}', name: 'movies_import')]
    public function debugTwig(KernelInterface $kernel, $pages = 1): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:import-popular-movies',
            'pages' => $pages
        ]);

        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true // true for decorated
        );
        $application->run($input, $output);

        $converter = new AnsiToHtmlConverter();
        $content = $output->fetch();

        return new Response($converter->convert($content));
    }
}