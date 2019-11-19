<?php

namespace Hostinger\Hevents;

use PHPUnit\Framework\TestCase;

class EventBagTest extends TestCase
{
    public function validEventProvider()
    {
        return [
            [
                [
                    [
                        'event'         => 'TEST_EVENT_1',
                        'properties'    => [],
                        'timestamp'     => 12345678,
                        'ignored_param' => 0.0,
                    ],
                    [
                        'event'      => 'TEST_EVENT_2',
                        'properties' => [
                            'test_1' => 123,
                            'test_2' => true,
                            'test_3' => '3',
                            'test_4' => null,
                        ],
                        'timestamp'  => 12345678,
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider validEventProvider
     * @param array $events
     */
    public function testBuildsEventBag(array $events)
    {
        $bag = new EventBag($events);
        $this->assertTrue($bag instanceof EventBag);
    }

    /**
     * @dataProvider validEventProvider
     * @param array $events
     */
    public function testParsesIndividualEvents(array $events)
    {
        $bag = new EventBag($events);
        $this->assertTrue($bag->getEvents()[0] instanceof Event);
    }

    /**
     * @dataProvider validEventProvider
     * @param array $events
     */
    public function testParsesCorrectEventData(array $events)
    {
        $bag = new EventBag($events);
        $this->assertEquals($events[0]['event'], ($bag->getEvents()[0])->getName());
    }

    /**
     * @dataProvider validEventProvider
     * @param array $events
     */
    public function testReturnsCorrectJson(array $events)
    {
        $bag      = new EventBag($events);
        $expected = '[{"event":"TEST_EVENT_1","properties":[],"timestamp":12345678},{"event":"TEST_EVENT_2","properties":{"test_1":123,"test_2":true,"test_3":"3","test_4":null},"timestamp":12345678}]';
        $this->assertEquals($expected, $bag->toString());
    }
}
