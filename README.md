[![alt text](https://www.flow.cl/images/header/logo-flow.svg)](https://www.flow.cl)
[![build status](https://travis-ci.com/darkghosthunter/flowsdk.svg?branch=master)](https://travis-ci.com/darkghosthunter/flowsdk)

# flow sdk 

the simplest (and unofficial) sdk for flow you will find.

[flow](https://www.flow.cl) is a chilean payment gateway that acts as a middleman for webpay plus, onepay, servipag, multicaja and cryptocompra.

with flow, you don't have to register in each service and comply with each of their sdk. flow will be in charge of the money collection and delivering to your commerce, whatever payment method the customer uses (and you want to enable). 

## requirements

* a [flow account](https://www.flow.cl/app/web/register.php)
* php 7.1.3+
* ext-openssl
* ext-curl

## install

install it in your project using composer.

```bash
composer require darkghosthunter/flow-sdk
```

### manual installation

if you don't have composer, you need to download `composer.phar`, and php manually if it's not available globally in your systems.

once you're done, download this package into your server (or your own computer), put the `composer.phar` file inside and let composer download the required packages.

```bash
cd /path/to/flow-sdk

path/to/php composer.phar install --no-dev
```

then load the package anywhere in your code calling the composer autoloader:

```php
<?php

// load the flow sdk.
include_once(__dir__ . '/path/to/flow-sdk/vendor/autoloader.php');

// load my app
include_once(__dir__ . '/www/index.php');
```

> if you did this in your own computer, its recommended to zip the package and upload it to your server, and there use a web ui file manager and decompress it. uploading multiple files may take you a lot of minutes instead of just one.

## usage

[refer to the wiki](wiki) for how to use this package.

## extending

this package uses [semantic versioning](https://semver.org/).

just issue a pr (pull request) with good code quality and all tests passed (or add new ones) for new functionality, bugs, cleaning or whatever.  

## roadmap

refer to the project part of this package.

## license

this package is licenced by the [mit license](license).

this package is not related in any way, directly or indirectly, to any of the services, companies, products and/or services referenced in this package.