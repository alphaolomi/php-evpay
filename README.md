# Payments with EvPay

[![Tests](https://github.com/alphaolomi/php-evpay/actions/workflows/run-tests.yml/badge.svg)](https://github.com/alphaolomi/php-evpay/actions/workflows/run-tests.yml)
[![Fix PHP code style issues](https://github.com/alphaolomi/php-evpay/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/alphaolomi/php-evpay/actions/workflows/fix-php-code-style-issues.yml)
[![Packagist Downloads](https://img.shields.io/packagist/dt/alphaolomi/php-evpay)](https://packagist.org/packages/alphaolomi/php-evpay)

## Installation

You can install the package via Composer:

```bash
composer require alphaolomi/laravel-evpay
```

## Usage

```php
use Alphaolomi\EvPay\EvPayService;

$evPayClient = new EvPayService([
    'name' => 'E-learning Platform',
    'username' => 'username',
    'callback' => 'https://e-learn-platform.com/callback',
]);

$response =  $evPayClient->paymentRequest([
    'mobileNo' => '0747991498',
    'amount' => 3000,
    'product' => 'Monthly Subscription',
    'network' => 'Mpesa',
]);

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

-   [Alpha Olomi](https://github.com/alphaolomi)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
