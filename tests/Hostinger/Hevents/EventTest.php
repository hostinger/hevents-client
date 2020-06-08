<?php

namespace Tests\Hostinger\Hevents;

use Exception;
use Hostinger\Hevents\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testCanGetName()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $this->assertEquals('test_name', $event->getName());
    }

    public function testCanSetName()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $event->setName('test_name2');
        $this->assertEquals('test_name2', $event->getName());
    }

    public function testCanGetProperties()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $this->assertEquals(['test_prop' => 'test_value'], $event->getProperties());
    }

    public function testCanSetProperties()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $event->setProperties(['test_prop2' => 'test_value2']);
        $this->assertEquals(['test_prop2' => 'test_value2'], $event->getProperties());
    }

    public function testCanCreateFromArray()
    {
        $event = Event::fromArray(['event' => 'test_name', 'properties' => ['test_prop' => 'test_value']]);
        $this->assertTrue($event instanceof Event);
    }

    public function testCreatesWithoutProperties()
    {
        $event = Event::fromArray(['event' => 'test_name']);
        self::assertTrue($event->getProperties() == []);
    }

    public function invalidEventArgumentProvider()
    {
        return [
            [['event' => true, 'properties' => ['test_prop' => 'test_value']]],
            [['event' => false, 'properties' => 'test_prop']],
            [['event' => null, 'properties' => ['test_prop']]],
            [['event' => 123.123, 'properties' => ['test_prop']]],
            [['properties' => ['test_prop' => 'test_value']]],
        ];
    }

    /**
     * @dataProvider invalidEventArgumentProvider
     * @param array $args
     * @throws Exception
     */
    public function testFailsWithInvalidEventArguments(array $args)
    {
        $this->expectException(Exception::class);
        Event::fromArray($args);
    }

    public function testSetsTimestamp()
    {
        $event = Event::fromArray([
            'event'      => 'test_name',
            'properties' => [
                'test_prop' => 'test_value',
            ],
        ]);
        $event->setTimestamp(12345678);
        $this->assertEquals(12345678, $event->getTimestamp());
    }

    public function testGetsTimestampFromArray()
    {
        $timestamp = 12345678;
        $event     = Event::fromArray([
            'event'      => 'test_name',
            'properties' => [
                'test_prop' => 'test_value',
            ],
            'timestamp'  => 12345678,
        ]);
        $this->assertEquals($timestamp, $event->getTimestamp());
    }
}
