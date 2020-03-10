# Plivo notifications channel for Laravel 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/plivo.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/plivo)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/plivo/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/plivo)
[![StyleCI](https://styleci.io/repos/65715218/shield)](https://styleci.io/repos/65715218)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/f4bd99c4-092c-4e36-a319-826f142c1ec4.svg?style=flat-square)](https://insight.sensiolabs.com/projects/f4bd99c4-092c-4e36-a319-826f142c1ec4)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/plivo.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/plivo)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/plivo/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/plivo/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/plivo.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/plivo)


This package makes it easy to send SMS notifications using [Plivo](https://plivo.com) with Laravel 5.5+, 6.x & 7.x.

## Contents

- [Installation](#installation)
	- [Setting up the Plivo service](#setting-up-the-Plivo-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install the package via composer:
```bash
composer require laravel-notification-channels/plivo
```


### Setting up your Plivo service
Log in to your [Plivo dashboard](https://manage.plivo.com/dashboard/) and grab your Auth Id, Auth Token and the phone number you're sending from. Add them to `config/services.php`.  

```php
// config/services.php
...
'plivo' => [
    'auth_id' => env('PLIVO_AUTH_ID'),
    'auth_token' => env('PLIVO_AUTH_TOKEN'),
    // Country code, area code and number without symbols or spaces
    'from_number' => env('PLIVO_FROM_NUMBER'),
],
```

## Usage

Follow Laravel's documentation to add the channel your Notification class:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Plivo\PlivoChannel;
use NotificationChannels\Plivo\PlivoMessage;

public function via($notifiable)
{
    return [PlivoChannel::class];
}

public function toPlivo($notifiable)
{
    return (new PlivoMessage)
                    ->content('This is a test SMS via Plivo using Laravel Notifications!');
}
```  

Add a `routeNotificationForPlivo` method to your Notifiable model to return the phone number:  

```php
public function routeNotificationForPlivo()
{
    // Country code, area code and number without symbols or spaces
    return preg_replace('/\D+/', '', $this->phone_number);
}
```    

### Available methods

* `content()` - (string), SMS notification body
* `from()` - (integer) Override default from number

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email sid@koomai.net instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Sid K](https://github.com/koomai)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
