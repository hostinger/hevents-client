# hevents-client

## Installation
In your composer.json add the repository:
```
"repositories":[
    {
        "type": "vcs",
        "url": "git@github.com:hostinger/hevents-client-php.git"
    }
],
"require": {
    "hostinger/hevents-client": "^1.0",
    ...
}
```
Run `composer install`

## Usage
The `emit` method takes an array of event details or one of the `Event` or `EventBag`.
The valid parameters that can be passed are `event`, `properties` and `timestamp`.

String `event` - the name of the event - is required.
Array `properties` can be skipped - the default value is an empty array.
Integer `timestamp` can be skipped - the default value is current timestamp.

#### Emiting a single event
```php
use Hostinger\Hevents\HeventsClient;

$event = [
    'event' => 'USER_SIGN_UP',
    'properties' => [
        'user_id' => 123,
        'time' => '2020-02-02',
        'details' => [
            'email' => 'ex@ample.com',
            'name' => 'Hevents'
        ]
    ],
    'timestamp' => time(),
];

$client = new HeventsClient('http://hevents.io', '938E5BF6213D34BD4C2EDF3C81E3E7BD80F52178F3B467643FE3D0F1E7377773');
$response = $client->emit($event);
```

#### Emiting multiple events at once

```php
use Hostinger\Hevents\EventBag;
use Hostinger\Hevents\HeventsClient;

$event1 = [
    'event' => 'USER_SIGN_UP',
    'properties' => [
        'user_id' => 123,
    ],
];

$event2 = [
    'event' => 'USER_FEEDBACK_GIVEN',
    'properties' => [
        'user_id' => 418,
        'feedback' => 'I am a teapot',
    ],
];

$events = [
    $event1, $event2,
];

$bag = new EventBag($events);
$client = new HeventsClient('http://hevents.io', '938E5BF6213D34BD4C2EDF3C81E3E7BD80F52178F3B467643FE3D0F1E7377773');
$response = $client->emit($bag);
```
