# Filament Wildcard Login 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digifactory/filament-wildcard-login.svg?style=flat-square)](https://packagist.org/packages/digifactory/filament-wildcard-login)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/digifactory/filament-wildcard-login/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/digifactory/filament-wildcard-login/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/digifactory/filament-wildcard-login/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/digifactory/filament-wildcard-login/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/digifactory/filament-wildcard-login.svg?style=flat-square)](https://packagist.org/packages/digifactory/filament-wildcard-login)


This package allows you to allow users with an e-mail address ending in specific domain names to login using an e-mail instead of using a password. After installing this package you can have a generic account (e.g. `helpdesk@digifactory.nl`) and everyone using a `@digifactory.nl` mail address can login using the e-mail sent directly to their inbox.

Since v1.0.2 it is also possible to enable e-mail login for *all users*. When enabled (`$plugin->allowAllDomains()`) the plugin will look for that specific user/e-mail address instead of a specific domain. This option can be enabled on top of the base functionality of this package. 

## Installation

You can install the package via composer:

```bash
composer require digifactory/filament-wildcard-login
```

## Usage

You can add the `FilamentWildcardLoginPlugin` to your Filament `Panel` like this:

```php
->plugins([
    FilamentWildcardLoginPlugin::make()
        ->domains([
            'digifactory.nl',
        ])
        ->loginDirectlyWithoutSendingEmail(app()->environment('local')),
])
```

These methods are available on the `FilamentWildcardLoginPlugin` instance:

| Method                                                            | Description                                                                                                                  |
|-------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------|
| `enabled(Closure \| bool $value = true)`                          | This method allows you to enable the plugin using a boolean or callback, by default the plugin is enabled.                   |
| `loginDirectlyWithoutSendingEmail(Closure \| bool $value = true)` | This method allows you to enable direct login, without sending the e-mail. This can be handy for local development.          |
| `domains(array $domains)`                                         | This method allows you to define the domains that can login using the e-mail link.                                           |
| `model(string $modelClass, string $modelColumn = 'email')`        | This method allows you to define the used `User` model and column, by default the plugin users `App\Model\User` and `email`. |
| `emailValidForMinutes(int $minutes)`                              | This method allows you to define after how many minutes the link in the e-mail should expire. The default is `5` minutes.    |
| `allowAllDomains(Closure \| bool $value = true)`                  | This method allows you to allow all e-mail address to login using an e-mail.                                                 |

## Preview

![preview](https://raw.githubusercontent.com/digifactory/filament-wildcard-login/main/docs/preview.jpg)
![preview-email](https://raw.githubusercontent.com/digifactory/filament-wildcard-login/main/docs/preview-email.jpg)

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
