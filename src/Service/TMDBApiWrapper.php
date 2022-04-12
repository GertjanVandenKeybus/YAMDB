<?php
namespace App\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBApiWrapper
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws HttpException
     */
    public function get(string $url, array $params): array
    {
        $params['api_key'] = $_ENV['TMDB_API_KEY'];
        $response = $this->client->request(
            'GET',
            $url,
            [
                'query' => $params
            ]
        );

        $statusCode = $response->getStatusCode();
        if($statusCode === 200){
            return $response->toArray();
        }else{
            throw new HttpException($statusCode, "TMDB API Call Failed");
        }
    }
}