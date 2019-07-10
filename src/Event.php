<?php

namespace Hostinger\Hevents;

/**
 * Class Event
 * @package Hostinger\Hevents
 */
class Event
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

    /**
     * Event constructor.
     * @param string $name
     * @param array $properties
     * @param null|int $timestamp
     */
    public function __construct($name, array $properties = [], $timestamp = null)
    {
        $this->name       = $name;
        $this->properties = $properties;
        if (!$timestamp) {
            $this->timestamp = time();
        } else {
            $this->timestamp = $timestamp;
        }
    }

    /**
     * @param array $params
     * @return Event
     */
    public static function fromArray(array $params)
    {
        self::validate(self::expectedArgs(), $params);
        return new Event($params['event'], $params['properties'], $params['timestamp']);
    }

    /**
     * @param array $expected
     * @param array $received
     */
    private static function validate(array $expected, array &$received)
    {
        foreach ($expected as $arg => $value) {
            if (isset($value['default']) && !isset($received[$arg])) {
                $received[$arg] = $value['default'];
            }
            if ($value['required'] && !isset($received[$arg])) {
                throw new \InvalidArgumentException("Required argument `{$arg}` is missing");
            }
            $type = gettype($received[$arg]);
            if ($type !== $value['type']) {
                throw new \InvalidArgumentException("Argument `{$arg}` is expected to be of type `{$value['type']}`, received `{$type}`");
            }
        }
    }

    /**
     * @return array
     */
    private static function expectedArgs()
    {
        return [
            'event'      => [
                'type'     => 'string',
                'required' => true,
            ],
            'timestamp'  => [
                'type'     => 'integer',
                'required' => true,
                'default'  => time(),
            ],
            'properties' => [
                'type'     => 'array',
                'required' => true,
                'default'  => [],
            ],
        ];
    }

    /**
     * @return string
     */
    public function toString()
    {
        return json_encode([
            'event'      => $this->getName(),
            'properties' => $this->getProperties(),
            'timestamp'  => $this->getTimestamp(),
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}