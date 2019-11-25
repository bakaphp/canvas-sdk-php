# Canvas APIs client library for PHP

The Kanvas PHP library provides convenient access to the Kanvas API from applications written in the PHP language. It includes a pre-defined set of classes for API resources that initialize themselves dynamically from API responses which makes it compatible with a wide range of versions of the Kanvas API. This SDK is based on Stripe SDK (thanks stripe)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP client library for accessing Canvas APIs

## Install

Via Composer

``` bash
$ composer require bakaphp/canvas-sdk-php
```

## Usage

``` php
use Canvas\Auth;
use Canvas\Canvas;
Canvas::setApiKey($appApiKey);
Auth::auth([
        'email' => 'kanvas@mctekk.com', 
        'password' => 'somethingpassword'
]);

//Call Kanvas Functions
```

Set the token on your DI
``` php
use Canvas\Auth;
use Canvas\Canvas;
Canvas::setApiKey($appApiKey);
Canvas::setAuthToken($request['token']);

//Call Kanvas Functions
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email max@mctekk.com instead of using the issue tracker.

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bakaphp/canvas-sdk-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bakaphp/canvas-sdk-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/bakaphp/canvas-sdk-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/bakaphp/canvas-sdk-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bakaphp/canvas-sdk-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bakaphp/canvas-sdk-php
[link-travis]: https://travis-ci.org/bakaphp/canvas-sdk-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/bakaphp/canvas-sdk-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/bakaphp/canvas-sdk-php
[link-downloads]: https://packagist.org/packages/bakaphp/canvas-sdk-php
[link-contributors]: ../../contributors
