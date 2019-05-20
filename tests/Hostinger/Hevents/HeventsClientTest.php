<?php

namespace Hostinger\Hevents;

use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
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

    public function testCreatesPostRequest()
    {
        $client  = new HeventsClient('http://test.domain.com', 'key');
        $request = $client->createRequest(['event' => 'test', 'properties' => []]);
        $this->assertTrue(
            $request instanceof Request
        );
        $this->assertTrue($request->getMethod() == 'POST');
    }

    public function testCreatesRequestWithAuthHeader()
    {
        $client  = new HeventsClient('http://test.domain.com', 'key');
        $request = $client->createRequest(['event' => 'test', 'properties' => []]);
        $this->assertTrue(
            $request->hasHeader('Authorization')
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

    public function clientUriProvider(): array
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
    public function testCanGetUrlWithEndpoint(string $uri, string $result)
    {
        $client = new HeventsClient($uri, 'key');
        $this->assertStringEndsWith(
            $result,
            $client->getFullUrl()
        );
    }
}