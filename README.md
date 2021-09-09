# laravel-base

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-base.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-base)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/rawilk/laravel-base/run-tests?label=tests)](https://github.com/rawilk/laravel-base/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-base.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-base)

**Note:** Package is still in early stages of development, so functionality is subject to change.

Laravel Base is a package I've created to provide functionality and blade components I commonly need in most projects without the need to keep duplicating
the code between projects. This package is very opinionated and may not be suitable for other people, so use at your own risk!

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-base
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Rawilk\LaravelBase\LaravelBaseServiceProvider" --tag="laravel-base-config"
```

You can view the default configuration here: https://github.com/rawilk/laravel-base/blog/main/config/laravel-base.php

## Usage

More documentation pending!

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
