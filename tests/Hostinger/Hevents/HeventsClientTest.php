<?php

namespace Tests\Hostinger\Hevents;

use Hostinger\Hevents\DefaultHandler;
use Hostinger\Hevents\Event;
use Hostinger\Hevents\EventBag;
use Hostinger\Hevents\HeventsClient;
use PHPUnit\Framework\TestCase;

class HeventsClientTest extends TestCase
{
    public function testConstructsClient()
    {
        $client = new HeventsClient('test.com', 'key');
        $this->assertTrue($client instanceof HeventsClient);
    }

    public function clientUriProvider()
    {
        return [
            [
                'http://test.domain.com',
                'http://test.domain.com/api/events',
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
                'https://test.domain.com',
            ],
            [
                'test.domain.com/test',
                'https://test.domain.com/test',
            ],
            [
                'test.domain.com/test/',
                'https://test.domain.com/test',
            ],
            [
                'https://test.domain.com/test/',
                'https://test.domain.com/test',
            ],
        ];
    }

    public function testReturnsBoolOnSuccessfulResponse()
    {
        $handler = $this->createMock(DefaultHandler::class);
        $handler->method('send')->willReturn(true);

        $client = new HeventsClient('test.com', 'key');
        $client->setHandler($handler);

        $this->assertTrue($client->emit(['event' => 'test', 'properties' => []]));
    }

    /**
     * @dataProvider clientUriProvider
     * @param string $uri
     * @param string $result
     */
    public function testCanGetUrlWithEndpoint($uri, $result)
    {
        $client = new HeventsClient($uri, 'key');
        $this->assertEquals($result, $client->getFullUrl($uri));
    }

    public function testEmitsEventObject()
    {
        $event = Event::fromArray(['event' => 'test', 'properties' => []]);

        $handler = $this->createMock(DefaultHandler::class);
        $handler->method('send')->willReturn(true);

        $client = new HeventsClient('test.com', 'key');
        $client->setHandler($handler);

        $this->assertTrue($client->emit($event));
    }

    public function testEmitsEventBagObject()
    {
        $eventBag = new EventBag([['event' => 'test', 'properties' => []]]);

        $handler = $this->createMock(DefaultHandler::class);
        $handler->method('send')->willReturn(true);

        $client = new HeventsClient('test.com', 'key');
        $client->setHandler($handler);

        $this->assertTrue($client->emit($eventBag));
    }
}
