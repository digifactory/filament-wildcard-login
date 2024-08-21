# Allow all e-mail addresses for a specific domain name to login to a generic account for that domain. 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digifactory/filament-wildcard-login.svg?style=flat-square)](https://packagist.org/packages/digifactory/filament-wildcard-login)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/digifactory/filament-wildcard-login/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/digifactory/filament-wildcard-login/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/digifactory/filament-wildcard-login/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/digifactory/filament-wildcard-login/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/digifactory/filament-wildcard-login.svg?style=flat-square)](https://packagist.org/packages/digifactory/filament-wildcard-login)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require digifactory/filament-wildcard-login
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-wildcard-login-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-wildcard-login-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentWildcardLogin = new DigiFactory\FilamentWildcardLogin();
echo $filamentWildcardLogin->echoPhrase('Hello, DigiFactory!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mark](https://github.com/mrk-j)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
