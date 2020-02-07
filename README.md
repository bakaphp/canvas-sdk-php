# Kanvas APIs client library for PHP

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
use Kanvas\Sdk\Auth;
use Kanvas\Sdk\Kanvas;
Kanvas::setApiKey($appApiKey);
Auth::auth([
        'email' => 'kanvas@mctekk.com', 
        'password' => 'somethingpassword'
]);

//Call Kanvas Functions
```

Set the token on your DI
``` php
use Kanvas\Sdk\Auth;
use Kanvas\Sdk\Kanvas;
Kanvas::setApiKey($appApiKey);
Kanvas::setAuthToken($request['token']);

//Call Kanvas Functions
```

## Using Resources

Every Kanvas SDK resource work in the same way. All of them have CRUD capabilites and some of them have custom functions that can be accessed as static functions. Furthermore, here is how a CRUD of a resource works:

### Create

``` php

use Kanvas\Sdk\Users;

Users::create([
    'firstname'=>'testSDK',
    'lastname'=> 'testSDK',
    'displayname'=> 'sdktester',
    'password'=> 'nosenose',
    'default_company'=> 'example sdk',
    'email'=> 'examplesd5k@gmail.com',
    'verify_password'=> 'nosenose'
    ]);

```

### Update

``` php

Users::update('id',[
    'firstname'=>'testSDK',
    'lastname'=> 'testSDK',
    ]);

```

### Delete

``` php
Users::delete('id');
```

### List

``` php
Users::all([], []);
```

### Retrieve

``` php
Users::retrieve('id', [], ['relationships'=>['roles']]);
```

## Custom queries on CRUD operations

In addition to the usual functionalities of every resource CRUD operation, other parameters can be used to make custom queries. An example of this can be seen on the retrieve operation example.

Currently we only support Phalcon's type of querying database tables. We work with:

- conditions
- limit
- order

## Phalcon Passthrough

To use the Phalcon Passthrough it must first be called as trait in your project's controller. The controller itself could be named whatever you want but the default name given is `ApiController`. Furthermore, the controller should extend from the Baka Http `BaseController`.

``` php

use Baka\Http\Api\BaseController as BakaBaseController;
use Kanvas\Sdk\Passthroughs\PhalconPassthrough;
use Phalcon\Http\Response;

class ApiController extends BakaBaseController
{
    use PhalconPassthrough;
}

```
The trait itself has a function called `transporter` which is in charge of making a request to the Kanvas API. Two functions must be created; one to be called by private routes and the other by public routes. They could be as follows:

``` php

use PhalconPassthrough;

public function publicTransporter(): Phalcon\Http\Response
{
    return $this->transporter();
}
public function privateTransporter(): Phalcon\Http\Response
{
    return $this->transporter();
}
```
Both functions should return a Phalcon Response.


### Setup of Passthrough Routes

There are two options for setting up the passthrough routes. The first one implies calling both PublicRoutes.php(contains the default Kanvas public routes) and PrivateRoutes.php(contains the default Kanvas private routes) directly on your own routes setup.

``` php

use Kanvas\Sdk\Routes\PrivateRoutes;
use Kanvas\Sdk\Routes\PublicRoutes;

```

The second one requires the creation of two files(one for public routes and one for private routes) that return the desired Kanvas routes as an array. These files should be as follows:

``` php

use Baka\Router\Route;

return [
    Route::crud('/users')->controller('ApiController')->action('privateTransporter')->notVia('post'),
    Route::crud('/companies')->controller('ApiController')->action('privateTransporter'),
    Route::crud('/roles')->controller('ApiController')->action('privateTransporter'),
    Route::crud('/locales')->controller('ApiController')->action('privateTransporter'),
    Route::crud('/currencies')->controller('ApiController')->action('privateTransporter'),
    Route::crud('/apps')->controller('ApiController')->action('privateTransporter')
    ]

```

The files must return array and every route should be a Baka Router Route.


After that, whichever option you choose should use the RouteConfigurator which has two functions for merging your own routes with the Kanvas ones.

``` php

use Kanvas\Sdk\Routes\RouteConfigurator;

$publicRoutes = RouteConfigurator::mergePublicRoutes($publicRoutes, appPath('api/routes/publicRoutes.php'));
$privateRoutes = RouteConfigurator::mergePrivateRoutes($privateRoutes, appPath('api/routes/privateRoutes.php'));

```

`mergePublicRoutes` merges Kanvas public routes with your own public routes. It also takes the path to the custom public routes file defined by you.

`mergePrivateRoutes` merges Kanvas private routes with your own private routes. It also takes the path to the custom private routes file defined by you.

Both functions return a merged array.


## SDK Models

This SDK also provides search by Kanvas Users and Companies. These two models will make a request to the Kanvas API and work just like a typical Phalcon model which has the `find` and `findFirst` functions.To use them do as follows:

``` php

use Kanvas\Sdk\Users;
use Kanvas\Sdk\Companies;

Users::find():

Users::findFirst([
    'conditions'=> 'email = ?0'
    'bind'=>[example]
]);

Companies::find();

Companies::findFirst([
    'conditions'=> 'email = ?0'
    'bind'=>[example]
]);

```

As said before, both models work as a Phalcon model, they work with all the parameters that can be given to them.


`Notice`: You must be authenticated to use this models and your API key must also be set.


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
