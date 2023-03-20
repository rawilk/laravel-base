# laravel-base

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/laravel-base.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-base)
![Tests](https://github.com/rawilk/laravel-base/workflows/Tests/badge.svg?style=flat-square)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/laravel-base.svg?style=flat-square)](https://packagist.org/packages/rawilk/laravel-base)

**Note:** Package is still in early stages of development, so functionality is subject to change.

LaravelBase is a package I've created to provide functionality and blade components I commonly need in most projects without the need to keep duplicating
the code between projects. This package is very opinionated and may not be suitable for other people, so use at your own risk!

If you are using Laravel Fortify and/or Jetstream, you **should not use** this package, as there will be conflicts between them. While I think those two packages are
great, I decided to take some of the functionality and create my own versions of some of it to satisfy my own needs and preferences. It is meant mainly for my
own personal use, but you are of course free to use it if it suits your project's needs.

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-base
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Rawilk\LaravelBase\LaravelBaseServiceProvider" --tag="laravel-base-config"
```

You can view the default configuration here: https://github.com/rawilk/laravel-base/blob/main/config/laravel-base.php

Alternatively you can run the install command this package provides, which will publish all of the package assets
and set up the providers for you.

```bash
php artisan laravel-base:install
```

**Warning:** You should not run this command on an existing project! When installing or updating on an existing project, you should manually
publish/configure as needed.

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

## Disclaimer

This package is not affiliated with, maintained, authorized, endorsed or sponsored by Laravel or any of its affiliates.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
