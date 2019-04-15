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
     * Event constructor.
     * @param string $name
     * @param array $properties
     */
    public function __construct(string $name, array $properties = [])
    {
        $this->name       = $name;
        $this->properties = $properties;
    }

    /**
     * @param array $params
     * @return Event
     */
    public static function fromArray(array $params): Event
    {
        self::validate(self::expectedArgs(), $params);
        return new Event($params['event'], $params['properties']);
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
    private static function expectedArgs(): array
    {
        return [
            'event'      => [
                'type'     => 'string',
                'required' => true,
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
    public function toString(): string
    {
        return json_encode([
            'event'      => $this->getName(),
            'properties' => $this->getProperties(),
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getProperties(): array
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
}