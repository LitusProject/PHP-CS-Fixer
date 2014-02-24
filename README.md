# Litus PHP CodeStyle

This project contains a custom fixer for [php-cs-fixer]. It a php file doesn't contain a license heade, it is added. If the license header in the php file is different than the supplied file, the php file is updated.

## Usage

Add the following to `composer.json`:

```json
{
    ...
    "require": {
        "litus/php-cs": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/LitusProject/PhpCodeStyle"
        }
    ],
    ...
}
```

Create a `.license_header` file (or pick any filename you want) and put the license header in this file.
__Note__: Add an unformatted version of the license header!

Create a `.php_cs` file:

```php
<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('vendor/')
    ->in(__DIR__);

return Litus\CodeStyle\Config\Config::create()
    ->setLicense(__DIR__ . '/.license_header') // or the filename you chose
    ->finder($finder);
```

## Code Style

We conform to the [PSR-2] and [PSR-4] coding styles.
