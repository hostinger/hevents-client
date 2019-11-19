<?php

namespace Hostinger\Hevents;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface HeventsClientInterface
{
    /**
     * @param array|EventDataInterface $event
     * @param bool $async
     * @return PromiseInterface|ResponseInterface
     */
    function emit($event, $async = null);

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
