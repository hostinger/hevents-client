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
            'key',
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
        $response = $client->post(['event' => 'test', 'properties' => []], true);
        $this->assertTrue(
            $response instanceof PromiseInterface
        );
    }

    public function testReturnsResponseInterfaceOnPostAsync()
    {
        $client   = new HeventsClient('http://test.domain.com', 'key');
        $response = $client->post(['event' => 'test', 'properties' => []]);
        $this->assertTrue(
            $response instanceof ResponseInterface
        );
    }
}