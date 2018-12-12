# Flow SDK for PHP 5.6
**[It's encouraged to use the `stable` branch for the most updated code.](https://github.com/DarkGhostHunter/FlowSdk/tree/stable)**
--- 

This is the legacy branch of Flow SDK, targeting specifically PHP 5.6.

This package is **not actively maintained**. It exists only for compatibility purposes in extreme conditions.

It *may* be compatible with PHP 5.5 and below. YMMV.

## Installation

You can just explicitly download with Composer the legacy version:

```bash
composer require darkghosthunter/flow-sdk:dev-legacy
```

If you don't have composer, you will have to install this package manually:

1) [Download this package](https://github.com/DarkGhostHunter/FlowSdk/archive/legacy.zip) and decompress it to a directory. 

2) [Download composer.phar](https://getcomposer.org/composer.phar) and put it inside the Flow SDK directory.

3) Install the libraries for this package.

```bash
cd /path/to/flow-sdk

path/to/php composer.phar install --optimize-autoloader --no-dev
```

4) Load the SDK into your project;

```php
<?php

// Load the Flow SDK.
include_once(__DIR__ . '/path/to/flow-sdk/vendor/autoloader.php');

// Load my app
include_once(__DIR__ . '/www/index.php');
```

## License

This package is licenced by the [MIT License](LICENSE).

This package is not related in any way, directly or indirectly, to any of the services, companies, products and/or services referenced in this package.