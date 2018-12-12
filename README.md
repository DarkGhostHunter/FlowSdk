![alt text](https://www.flow.cl/images/header/logo-flow.svg)
[![Latest Stable Version](https://poser.pugx.org/darkghosthunter/flow-sdk/v/stable)](https://packagist.org/packages/darkghosthunter/flow-sdk) [![License](https://poser.pugx.org/darkghosthunter/flow-sdk/license)](https://packagist.org/packages/darkghosthunter/flow-sdk)
![](https://img.shields.io/packagist/php-v/darkghosthunter/flow-sdk.svg)
 [![Build Status](https://travis-ci.com/DarkGhostHunter/FlowSdk.svg?branch=master)](https://travis-ci.com/DarkGhostHunter/FlowSdk) [![Coverage Status](https://coveralls.io/repos/github/DarkGhostHunter/FlowSdk/badge.svg?branch=master)](https://coveralls.io/github/DarkGhostHunter/FlowSdk?branch=master) [![Maintainability](https://api.codeclimate.com/v1/badges/0138e0686180120e68c5/maintainability)](https://codeclimate.com/github/DarkGhostHunter/FlowSdk/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/0138e0686180120e68c5/test_coverage)](https://codeclimate.com/github/DarkGhostHunter/FlowSdk/test_coverage)

# Flow SDK 

The simplest (and unofficial) SDK for Flow you will find.

[Flow](https://www.flow.cl) is a chilean payment gateway that acts as a middleman for Webpay Plus, Onepay, Servipag, Multicaja and CryptoCompra.

With Flow, you don't have to register in each service and comply with each of their SDK, APIs or contracts. Flow will be in charge of the money collection and delivering to your commerce, whatever payment method the customer uses (and you want to enable), through a single common interface.

## Requirements

* A [Flow account](https://www.flow.cl/app/web/register.php)
* PHP 7.1.3+
* ext-json
* ext-openssl
* ext-curl

> If you need PHP 5.6 compatibility, you can check the [`legacy` branch](#php-56-compatibility).

## Install

Install it in your project using [Composer](https://getcomposer.org).

```bash
composer require darkghosthunter/flow-sdk
```

### Manual installation

If you don't have Composer, you need to [download `composer.phar`](https://getcomposer.org/composer.phar), and PHP manually if it's not available globally in your systems.

Once you're done, download this package into your server (or your own computer) and put the `composer.phar` file inside. Then, let Composer download the required packages:

```bash
cd /path/to/flow-sdk

path/to/php composer.phar install --optimize-autoloader --apcu-autoloader --no-dev
```

Then load the package anywhere in your code calling the Composer autoloader:

```php
<?php

// Load the Flow SDK.
include_once(__DIR__ . '/path/to/flow-sdk/vendor/autoloader.php');

// Load my app
include_once(__DIR__ . '/www/index.php');
```

> If you did this in your own computer, its recommended to zip the package and upload it to your server, and there use a Web UI File Manager and decompress it. Uploading multiple files may take you a lot of minutes instead of just one, specially under FTP.

## Usage

Flow SDK was made to be very expressive but straightforward. This code should tell what we are doing without having to read the manual.

```php
<?php

use DarkGhostHunter\FlowSdk\Flow;

$flow = Flow::make('production', [
    'apiKey'    => '1F90971E-8276-4713-97FF-2BLF5091EE3B',
    'secret'    => 'f8b45f9b8bcdb5702dc86a1b894492303741c405',
]);

$paymentResponse = $flow->payment()->commit([
    'commerceOrder'     => 'order#123',
    'subject'           => 'Console',
    'amount'            => 99990,
    'email'             => 'johndoe@mail.com',
    'urlConfirmation'   => 'https://myapp.com/flow/confirm',
    'urlReturn'         => 'https://myapp.com/flow/return',
    'optional'          => [
        'Message' => 'Your order is being shipped!'
    ]
]);

header('Location: '. $paymentResponse->getUrl());
```

Of course, is always recommended to [RTFM](http://lmgtfy.com/?q=RTFM). [Refer to the Wiki](https://github.com/DarkGhostHunter/FlowSdk/wiki) to see how to use all the package in detail.

## Extending

This package uses [Semantic Versioning](https://semver.org/).

Just issue a PR (Pull Request) with good code quality and all tests passed (or add new ones) for new functionality, bugs, cleaning or whatever.

## Examples

If you're lost and want some kind of quick prototyping, or you just want to know how a particular transaction process works, look into the [`examples`](examples) directory.

## PHP 5.6 Compatibility

Check out the [`legacy` branch](tree/legacy) for PHP 5.6 compatibility. Since it's considered *legacy*, there is no promises on keeping it up-to-date.

Consider migrating to PHP 7.1 and above as soon as possible. PHP 5.6 and below, as well PHP 7.0 and below, will be no longer be supported by January 1th, 2019. **Security releases won't be available for old PHP version after that date**.[¹](http://php.net/supported-versions.php).

## License

This package is licenced by the [MIT License](LICENSE).

This package is not related in any way, directly or indirectly, to any of the services, companies, products and/or services referenced in this package.