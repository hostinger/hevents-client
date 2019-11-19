<?php


namespace Hostinger\Hevents;


class EventBag implements EventDataInterface
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * EventBag constructor.
     * @param array $events
     */
    public function __construct(array $events)
    {
        foreach ($events as $event) {
            $this->appendEvent($event);
        }
    }

    /**
     * @param array $event
     */
    public function appendEvent(array $event)
    {
        array_push($this->events, Event::fromArray($event));
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return json_encode(
            array_map(function ($event) {
                return $event->toArray();
            }, $this->events)
        );
    }
}
