<?php


namespace Hostinger\Hevents;


use GuzzleHttp\Client;

class DefaultHandler implements RequestHandlerInterface
{
    protected $client;

    public function __construct(string $url, string $key, float $timeout)
    {
        $this->client = new Client(
            [
                'base_uri' => $url,
                'timeout'  => $timeout,
                'headers'  => [
                    'accept'        => 'application/json',
                    'content-type'  => 'application/json',
                    'authorization' => 'Bearer '.$key,
                ],
            ]
        );
    }

    public function send(array $event): bool
    {
        return $this->client->post(null, ['json' => $event])->getStatusCode() == 200;
    }
}
