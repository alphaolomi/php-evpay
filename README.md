# Payments with Evpay

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphaolomi/laravel-evpay.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/laravel-evpay)
[![Tests](https://img.shields.io/github/actions/workflow/status/alphaolomi/laravel-evpay/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alphaolomi/laravel-evpay/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/alphaolomi/laravel-evpay.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/laravel-evpay)

## Installation

You can install the package via composer:

```bash
composer require alphaolomi/laravel-evpay
```

## Usage

```php
use Alphaolomi\EvPay\EvPayService;

$evPayClient = new EvPayService([
    'username' => 'username',
    'environment' => 'testing',
]);
$response =  $evPayClient->pay([]);

var_dump($response);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alpha Olomi](https://github.com/alphaolomi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
