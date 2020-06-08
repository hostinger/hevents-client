<?php


namespace Hostinger\Hevents;


class EventBag implements EventDataInterface
{
    protected $events = [];

    public function __construct(array $events)
    {
        foreach ($events as $event) {
            $this->appendEvent($event);
        }
    }

    public function appendEvent(array $event)
    {
        array_push($this->events, Event::fromArray($event));
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function toArray(): array
    {
        return array_map(function (Event $event) {
            return $event->toArray();
        }, $this->getEvents());
    }
}
