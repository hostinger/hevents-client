<?php

namespace Hostinger\Hevents;

use Exception;

class HeventsClient
{
    const DEFAULT_PROTOCOL = 'https';
    const ENDPOINT_PATH    = '/api/events';

    /**
     * @var RequestHandlerInterface
     */
    protected $handler;

    public function __construct(string $url, string $key, float $timeout = 4)
    {
        $this->handler = $this->createDefaultHandler($url, $key, $timeout);
    }

    /**
     * @param array|EventDataInterface $event
     * @return bool
     * @throws Exception
     */
    public function emit($event)
    {
        if (is_array($event)) {
            $event = Event::fromArray($event);
        }

        return $this->getHandler()->send($event->toArray());
    }

    public function setHandler(RequestHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }

    public function createDefaultHandler(string $url, string $key, float $timeout): RequestHandlerInterface
    {
        return new DefaultHandler($this->getFullUrl($url), $key, $timeout);
    }

    public function getFullUrl(string $url): string
    {
        $urlParts = parse_url(rtrim($url, '/'));

        $scheme = array_key_exists('scheme', $urlParts) ? $urlParts['scheme'] : self::DEFAULT_PROTOCOL;
        $url    = array_key_exists('host', $urlParts) ? $urlParts['host'] : '';
        $uri    = array_key_exists('path', $urlParts) ? $urlParts['path'] : self::ENDPOINT_PATH;

        return "{$scheme}://{$url}{$uri}";
    }
}
