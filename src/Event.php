<?php

namespace Hostinger\Hevents;

use Exception;

class Event implements EventDataInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var int
     */
    protected $timestamp;

    public function __construct(string $name, array $properties = [], ?int $timestamp = null)
    {
        $this->name       = $name;
        $this->properties = $properties;

        if (!$timestamp) {
            $this->timestamp = time();
        } else {
            $this->timestamp = $timestamp;
        }
    }

    public static function fromArray(array $params): Event
    {
        if (!isset($params['event']) || empty($params['event']) || !is_string($params['event'])) {
            throw new Exception('Event name is invalid or not provided');
        }

        if (!isset($params['properties']) || !is_array($params['properties'])) {
            $params['properties'] = [];
        }

        if (!isset($params['timestamp']) || !is_int($params['timestamp'])) {
            $params['timestamp'] = time();
        }

        return new Event($params['event'], $params['properties'], $params['timestamp']);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function toArray(): array
    {
        return [
            'event'      => $this->getName(),
            'properties' => $this->getProperties(),
            'timestamp'  => $this->getTimestamp(),
        ];
    }
}
