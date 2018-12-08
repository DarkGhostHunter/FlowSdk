[![alt text](https://www.flow.cl/images/header/logo-flow.svg)](https://www.flow.cl)
[![Build Status](https://travis-ci.com/DarkGhostHunter/FlowSdk.svg?branch=master)](https://travis-ci.com/DarkGhostHunter/FlowSdk) [![Coverage Status](https://coveralls.io/repos/github/DarkGhostHunter/FlowSdk/badge.svg?branch=master)](https://coveralls.io/github/DarkGhostHunter/FlowSdk?branch=master) [![Maintainability](https://api.codeclimate.com/v1/badges/0138e0686180120e68c5/maintainability)](https://codeclimate.com/github/DarkGhostHunter/FlowSdk/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/0138e0686180120e68c5/test_coverage)](https://codeclimate.com/github/DarkGhostHunter/FlowSdk/test_coverage)

# Flow SDK 

The simplest (and unofficial) SDK for Flow you will find.

[Flow](https://www.flow.cl) is a chilean payment gateway that acts as a middleman for Webpay Plus, Onepay, Servipag, Multicaja and CryptoCompra.

With Flow, you don't have to register in each service and comply with each of their SDK, APIs or contracts. Flow will be in charge of the money collection and delivering to your commerce, whatever payment method the customer uses (and you want to enable), through a single common interface.

## Requirements

* A [Flow account](https://www.flow.cl/app/web/register.php)
* PHP 7.1.3+
* ext-openssl
* ext-curl

## Install

Install it in your project using Composer.

```bash
composer require darkghosthunter/flow-sdk
```

### Manual installation

If you don't have Composer, you need to download `composer.phar`, and PHP manually if it's not available globally in your systems.

Once you're done, download this package into your server (or your own computer), put the `composer.phar` file inside and let Composer download the required packages.

```bash
cd /path/to/flow-sdk

path/to/php composer.phar install --no-dev
```

Then load the package anywhere in your code calling the Composer autoloader:

```php
<?php

// Load the Flow SDK.
include_once(__DIR__ . '/path/to/flow-sdk/vendor/autoloader.php');

// Load my app
include_once(__DIR__ . '/www/index.php');
```

> If you did this in your own computer, its recommended to zip the package and upload it to your server, and there use a Web UI File Manager and decompress it. Uploading multiple files may take you a lot of minutes instead of just one.

## Usage

[Refer to the Wiki](https://github.com/DarkGhostHunter/FlowSdk/wiki) for how to use this package.

## Extending

This package uses [Semantic Versioning](https://semver.org/).

Just issue a PR (Pull Request) with good code quality and all tests passed (or add new ones) for new functionality, bugs, cleaning or whatever.

## License

This package is licenced by the [MIT License](LICENSE).

This package is not related in any way, directly or indirectly, to any of the services, companies, products and/or services referenced in this package.