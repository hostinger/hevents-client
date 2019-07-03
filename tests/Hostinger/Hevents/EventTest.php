<?php

namespace Hostinger\Hevents;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testCanGetName()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $this->assertEquals(
            'test_name',
            $event->getName()
        );
    }

    public function testCanSetName()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $event->setName('test_name2');
        $this->assertEquals(
            'test_name2',
            $event->getName()
        );
    }

    public function testCanGetProperties()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $this->assertEquals(
            ['test_prop' => 'test_value'],
            $event->getProperties()
        );
    }

    public function testCanSetProperties()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $event->setProperties(['test_prop2' => 'test_value2']);
        $this->assertEquals(
            ['test_prop2' => 'test_value2'],
            $event->getProperties()
        );
    }

    public function testCanGetString()
    {
        $event = new Event('test_name', ['test_prop' => 'test_value']);
        $this->assertEquals(
            '{"event":"test_name","properties":{"test_prop":"test_value"}}',
            $event->toString()
        );
    }

    public function testCanCreateFromArray()
    {
        $event = Event::fromArray(['event' => 'test_name', 'properties' => ['test_prop' => 'test_value']]);
        $this->assertTrue(
            $event instanceof Event
        );
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
            [['properties' => ['test_prop' => 'test_value']]],
            [['event' => true, 'properties' => 'test_prop']],
        ];
    }

    /**
     * @dataProvider invalidEventArgumentProvider
     * @param array $args
     */
    public function testFailsWithInvalidEventArguments(array $args)
    {
        $this->expectException(\InvalidArgumentException::class);
        Event::fromArray($args);
    }

    public function testCanGetStringFromArray()
    {
        $event = Event::fromArray(['event' => 'test_name', 'properties' => ['test_prop' => 'test_value']]);
        $this->assertEquals(
            '{"event":"test_name","properties":{"test_prop":"test_value"}}',
            $event->toString()
        );
    }
}