# laravel-base

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-base.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-base)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/rawilk/laravel-base/run-tests?label=tests)](https://github.com/rawilk/laravel-base/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-base.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-base)

---
This repo can be used to scaffold a Laravel package. Follow these steps to get started:

1. Press the "Use template" button at the top of this repo to create a new repo with the contents of this laravel-base.
2. Run `./configure-laravel-base.sh` to run the script that will replace all placeholders throughout all the files.
3. Remove this block of text.
---

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-base
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Rawilk\LaravelBase\LaravelBaseServiceProvider" --tag="laravel-base-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Rawilk\LaravelBase\LaravelBaseServiceProvider" --tag="laravel-base-config"
```

You can view the default configuration here: https://github.com/rawilk/laravel-base/blog/main/config/laravel-base.php

## Usage

``` php
$laravel-base = new Rawilk\LaravelBase;
echo $laravel-base->echoPhrase('Hello, Rawilk!');
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

Please review [my security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Randall Wilk](https://github.com/rawilk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
