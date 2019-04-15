# hevents-client

## Installation
In your composer.json add the repository:
```
"repositories":[
    {
        "type": "vcs",
        "url": "git@github.com:hostinger/hevents-client.git"
    }
]
```
Run `composer install`

## Usage
The `emit` method takes an array of event details.
The valid parameters that can be passed are `event` and `properties`.

String `event` - the name of the event - is required.
Array `properties` can be skipped - the default value is an empty array.

```php
use Hostinger\Hevents\HeventsClient;

$event = [
    'event' => 'USER_SIGN_UP',
    'properties => [
        'user_id' => 123,
        'time' => '2020-02-02',
        'details' => [
            'email' => 'ex@ample.com',
            'name' => 'Hevents'
        ]
    ]
];

$client = new HeventsClient('http://hevents.io', '938E5BF6213D34BD4C2EDF3C81E3E7BD80F52178F3B467643FE3D0F1E7377773');
$response = $client->emit($event);
```