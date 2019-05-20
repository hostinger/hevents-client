<?php

namespace Hostinger\Hevents;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HeventsClient
 * @package Hostinger\Hevents
 */
class HeventsClient implements HeventsClientInterface
{
    const DEFAULT_PROTOCOL = 'https';
    const ENDPOINT_URI     = '/api/events';

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $headers;

    /**
     * HeventsClient constructor.
     * @param string $url
     * @param string $key
     */
    public function __construct(string $url, string $key)
    {
        $this->setUrl($url);
        $this->setKey($key);
        $this->setUpHttpClient($this->getUrl());
        $this->setAuthorizationHeader($this->getKey());
    }

    /**
     * @param string $url
     */
    private function setUpHttpClient(string $url)
    {
        $this->client = new Client(
            [
                'base_uri' => $url,
                'timeout'  => 20,
                'headers'  => [
                    'accept'       => 'application/json',
                    'content-type' => 'application/json',
                ],
            ]
        );
    }

    /**
     * @param string $key
     */
    private function setAuthorizationHeader(string $key)
    {
        $this->appendHeaders('Authorization', "Bearer $key");
    }

    /**
     * @param array $event
     * @param bool $async
     * @return PromiseInterface|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function emit(array $event, bool $async = false)
    {
        $request = $this->createRequest($event);
        if ($async) {
            return $this->sendAsync($request);
        }
        return $this->send($request);
    }

    /**
     * @param string $header
     * @param string $value
     */
    public function appendHeaders(string $header, string $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $event
     * @return Request
     */
    public function createRequest(array $event): Request
    {
        return new Request(
            'POST',
            $this->getFullUrl(),
            $this->getHeaders(),
            Event::fromArray($event)->toString()
        );
    }

    /**
     * @param Request $request
     * @return PromiseInterface
     */
    public function sendAsync(Request $request): PromiseInterface
    {
        return $this->client->sendAsync($request);
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(Request $request): ResponseInterface
    {
        return $this->client->send($request);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getFullUrl(): string
    {
        $urlParts = parse_url(rtrim($this->getUrl(), '/'));

        $scheme = array_key_exists('scheme', $urlParts) ? $urlParts['scheme'] : self::DEFAULT_PROTOCOL;
        $url    = array_key_exists('host', $urlParts) ? $urlParts['host'] : '';
        $uri    = array_key_exists('path', $urlParts) ? $urlParts['path'] : $this->getEndpoint();

        return "{$scheme}://{$url}{$uri}";
    }

    /**
     * @return string
     */
    private function getEndpoint(): string
    {
        return self::ENDPOINT_URI;
    }
}