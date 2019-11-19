<?php

namespace Hostinger\Hevents;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
    public function __construct($url, $key)
    {
        $this->setUrl($url);
        $this->setKey($key);
        $this->setUpHttpClient($this->getUrl());
        $this->setAuthorizationHeader($this->getKey());
    }

    /**
     * @param string $url
     */
    private function setUpHttpClient($url)
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
    private function setAuthorizationHeader($key)
    {
        $this->appendHeaders('Authorization', "Bearer $key");
    }

    /**
     * @param array|EventDataInterface $event
     * @param null $async
     * @return PromiseInterface|ResponseInterface
     * @throws GuzzleException
     */
    public function emit($event, $async = null)
    {
        if (is_array($event)) {
            $event = Event::fromArray($event);
        }

        $request = $this->createRequest($event->toString());

        if ($async === true) {
            return $this->sendAsync($request);
        }

        return $this->send($request);
    }

    /**
     * @param string $header
     * @param string $value
     */
    public function appendHeaders($header, $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $data
     * @return Request
     */
    protected function createRequest($data)
    {
        return new Request(
            'POST',
            $this->getFullUrl(),
            $this->getHeaders(),
            $data
        );
    }

    /**
     * @param Request $request
     * @return PromiseInterface
     */
    public function sendAsync(Request $request)
    {
        return $this->client->sendAsync($request);
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function send(Request $request)
    {
        return $this->client->send($request);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getFullUrl()
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
    private function getEndpoint()
    {
        return self::ENDPOINT_URI;
    }
}
