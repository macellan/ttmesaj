# TTMesaj Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/macellan/ttmesaj.svg?style=flat-square)](https://packagist.org/packages/macellan/ttmesaj)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/macellan/ttmesaj/master.svg?style=flat-square)](https://travis-ci.org/macellan/ttmesaj)
[![StyleCI](https://styleci.io/repos/243007838/shield)](https://styleci.io/repos/243007838)
[![Quality Score](https://img.shields.io/scrutinizer/g/macellan/ttmesaj.svg?style=flat-square)](https://scrutinizer-ci.com/g/macellan/ttmesaj)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/macellan/ttmesaj/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/macellan/ttmesaj/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/macellan/ttmesaj.svg?style=flat-square)](https://packagist.org/packages/macellan/ttmesaj)

This package makes it easy to send notifications using [TTMesaj](https://ttmesaj.com) with Laravel 5.5+ and 6.0

## Contents

- [Installation](#installation)
    - [Setting up the TTmesaj service](#setting-up-the-TTMesaj-service)
- [Usage](#usage)
    - [ On-Demand Notifications](#on-demand-notifications)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install this package via composer:

``` bash
composer require macellan/ttmesaj
```

### Setting up the TTMesaj service

Add your TTMesaj sms gate login, password and default sender name to your config/services.php:

```php
// config/services.php
...
    'ttmesaj' => [
        'wsdlEndpoint' => env('TTMESAJ_WSDL_ENDPOINT', 'https://ws.ttmesaj.com/Service1.asmx?WSDL'),
        'username' => env('TTMESAJ_USERNAME'),
        'password' => env('TTMESAJ_PASSWORD'),
        'origin' => env('TTMESAJ_ORIGIN'),
        'enable' => env('TTMESAJ_ENABLE', true),
        'debug' => env('TTMESAJ_DEBUG', false), //will log sending attempts and results
        'sandboxMode' => env('TTMESAJ_SANDBOX_MODE', false) //will not invoke API call
    ],
...
```

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Macellan\TTMesaj\TTMesajMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ['ttmesaj'];
    }

    public function toTTMesaj($notifiable)
    {
        return TTMesajMessage::create()
            ->setBody('Your account was approved!')
            ->setSendTime(Carbon::now())
            ->setEndTime(Carbon::now()->addDay());  
    }
}
```

In your notifiable model, make sure to include a routeNotificationForTTMesaj() method, which returns a phone number or an array of phone numbers.

```php
public function routeNotificationForTTMesaj()
{
    return str_replace(['+', ' '], '', $this->phone);
}
```

### On-Demand Notifications

Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification:

```php
Notification::route('ttmesaj', '905322234433')  
            ->notify(new AccountApproved());
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Security

If you discover any security related issues, please email fatih@aytekin.me instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Fatih Aytekin](https://github.com/faytekin)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
