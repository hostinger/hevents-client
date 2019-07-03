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
    function emit(array $event, $async = null);

    /**
     * @param string $header
     * @param string $value
     */
    function appendHeaders($header, $value);

    /**
     * @return array
     */
    function getHeaders();

    /**
     * @param array $event
     * @return Request
     */
    function createRequest(array $event);

    /**
     * @param Request $request
     * @return PromiseInterface
     */
    function sendAsync(Request $request);

    /**
     * @param Request $request
     * @return ResponseInterface
     */
    function send(Request $request);

    /**
     * @return string
     */
    function getUrl();

    /**
     * @param string $url
     */
    function setUrl($url);

    /**
     * @return string
     */
    function getKey();

    /**
     * @param string $key
     */
    function setKey($key);

    /**
     * @return string
     */
    function getFullUrl();
}