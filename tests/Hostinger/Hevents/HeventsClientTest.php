<?php

namespace Hostinger\Hevents;

use GuzzleHttp\Promise\PromiseInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class HeventsClientTest extends TestCase
{
    public function testCanUseHeventsEndpoint()
    {
        $client = new HeventsClient('http://test.domain.com', 'key');
        $this->assertEquals(
            'http://test.domain.com',
            $client->getUrl()
        );
    }

    public function testHasApiKeyAuthHeader()
    {
        $client = new HeventsClient('http://test.domain.com', 'key');
        $this->assertArrayHasKey(
            'Authorization',
            $client->getHeaders()
        );
        $this->assertEquals(
            'Bearer key',
            $client->getHeaders()['Authorization']
        );
    }

    public function testReturnsPromiseInterfaceOnPostAsync()
    {
        $client   = new HeventsClient('http://test.domain.com', 'key');
        $response = $client->emit(['event' => 'test', 'properties' => []], true);
        $this->assertTrue(
            $response instanceof PromiseInterface
        );
    }

    public function testReturnsResponseInterfaceOnPostAsync()
    {
        $client   = new HeventsClient('https://jsonplaceholder.typicode.com/posts', 'key');
        $response = $client->emit(['event' => 'test', 'properties' => []]);
        $this->assertTrue(
            $response instanceof ResponseInterface
        );
    }

    public function clientUriProvider()
    {
        return [
            [
                'http://test.domain.com',
                'http://test.domain.com/api/events'
            ],
            [
                'http://test.domain.com/',
                'http://test.domain.com/api/events',
            ],
            [
                'https://test.domain.com',
                'https://test.domain.com/api/events',
            ],
            [
                'https://test.domain.com/',
                'https://test.domain.com/api/events',
            ],
            [
                'https://test.domain.com/',
                'https://test.domain.com/api/events',
            ],
            [
                'test.domain.com/',
                'test.domain.com',
            ],
            [
                'test.domain.com/test',
                'test.domain.com/test',
            ],
            [
                'test.domain.com/test/',
                'test.domain.com/test',
            ],
            [
                'https://test.domain.com/test/',
                'https://test.domain.com/test',
            ],
        ];
    }

    /**
     * @dataProvider clientUriProvider
     * @param string $uri
     * @param string $result
     */
    public function testCanGetUrlWithEndpoint($uri, $result)
    {
        $client = new HeventsClient($uri, 'key');
        $this->assertStringEndsWith(
            $result,
            $client->getFullUrl()
        );
    }

    public function testCanCallWithTestCredentials()
    {
        $host = 'https://hevents.hostinger.io';
        $key  = '4b6yB5kKSH9A2Qgl4YAXtQI58H5z12rTTMQD68v5wMCFkp1ImRDQOHAy6Dmx';

        $data = [
            'event'      => 'hevents.client.test',
            'properties' => [
                'testint'   => 1,
                'testarray' => [
                    'testfloat'  => 1.1,
                    'teststring' => 'string'
                ]
            ],
        ];

        $client   = new HeventsClient($host, $key);
        $response = $client->emit($data);
        $this->assertEquals('200', $response->getStatusCode());
    }

    public function testEmitsEventObject()
    {
        $event    = Event::fromArray(['event' => 'test', 'properties' => []]);
        $client   = new HeventsClient('https://jsonplaceholder.typicode.com/posts', 'key');
        $response = $client->emit($event);
        $this->assertTrue(
            $response instanceof ResponseInterface
        );
    }

    public function testEmitsEventBagObject()
    {
        $eventBag = new EventBag([['event' => 'test', 'properties' => []]]);
        $client   = new HeventsClient('https://jsonplaceholder.typicode.com/posts', 'key');
        $response = $client->emit($eventBag);
        $this->assertTrue(
            $response instanceof ResponseInterface
        );
    }
}
