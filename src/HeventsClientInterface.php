<?php

namespace Hostinger\Hevents;

use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

interface HeventsClientInterface
{
    /**
     * @param array $event
     * @param bool $async
     * @return PromiseInterface|ResponseInterface
     */
    function emit(array $event, bool $async = false);

    /**
     * @param string $header
     * @param string $value
     */
    function appendHeaders(string $header, string $value);

    /**
     * @return array
     */
    function getHeaders(): array;

    /**
     * @param array $event
     * @return Request
     */
    function createRequest(array $event): Request;

    /**
     * @param Request $request
     * @return PromiseInterface
     */
    function sendAsync(Request $request): PromiseInterface;

    /**
     * @param Request $request
     * @return ResponseInterface
     */
    function send(Request $request): ResponseInterface;

    /**
     * @return string
     */
    function getUrl(): string;

    /**
     * @param string $url
     */
    function setUrl(string $url);

    /**
     * @return string
     */
    function getKey(): string;

    /**
     * @param string $key
     */
    function setKey(string $key);

    /**
     * @return string
     */
    function getFullUrl(): string;
}