# PHP FunPay SMS Parser

![ak-php-funpay-sms-parser](https://user-images.githubusercontent.com/1849174/42195292-ec2e0330-7e80-11e8-9069-6046946ccb25.png)

<p align="center">
<a href="https://travis-ci.org/a-komarev/php-funpay-sms-parser"><img src="https://img.shields.io/travis/a-komarev/php-funpay-sms-parser/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://styleci.io/repos/139517157"><img src="https://styleci.io/repos/139517157/shield" alt="StyleCI"></a>
</p>

## Introduction

PHP FunPay SMS Parser library allows to parse SMS messages from transaction confirmation gateway.

## Contents

- [Installation](#installation)
  - [Install as composer package](#install-as-package)
  - [Standalone](#standalone)
- [Demo](#demo)
- [Usage](#usage)
  - [Instantiate Parser](#instantiate-parser)
  - [Available Methods](#available-methods)
  - [Exceptions](#exceptions)
- [Testing](#testing)
- [Author](#author)
- [License](#license)

## Installation

### Install as package

Pull in the package through Composer in your application:

```sh
$ composer install antonkomarev/php-funpay-sms-parser
```

### Standalone

Clone or download project from [PHP FunPay SMS Parser git repository](https://github.com/a-komarev/php-funpay-sms-parser).

```sh
$ git clone git@github.com:a-komarev/php-funpay-sms-parser.git && cd ./php-funpay-sms-parser
```

Generate class autoload file and install PHPUnit.

```sh
$ composer install
```

## Demo

Demo script could be executed using PHP CLI:

```sh
$ php public/demo.php
```

You could experiment with `$message` variable value.

More examples could be found in `tests/ParserTest.php` file.

## Usage

### Instantiate Parser

```php
$message = '
    Пароль: 0823
    Спишется 100,85р.
    Перевод на счет 410017080996934
';

$parsedMessage = new \AK\FunPay\SmsParser\Parser($message);
```

### Available Methods

#### Get Yandex.Money account number

```php
$parsedMessage->account(): string
```

#### Get transaction money amount

```php
$parsedMessage->moneyAmount(): float
```

#### Get confirmation pin-code

```php
$parsedMessage->pin(): string
```

### Exceptions

- `ParserException` (abstract)
- `MoneyAmountNotFound`
- `PinNotFound`
- `YandexAccountNotFound`
- `YandexAccountInvalid`

## Testing

Run the tests with:

```sh
$ vendor/bin/phpunit
```

## Author

| <a href="https://github.com/a-komarev">![@a-komarev](https://avatars.githubusercontent.com/u/1849174?s=110)<br />Anton Komarev</a> |
| :---: |

## License

- `PHP FunPay SMS Parser` package is open-sourced software licensed under the [MIT license](LICENSE) by Anton Komarev.
- `Decomposition` image licensed under [Creative Commons 3.0](https://creativecommons.org/licenses/by/3.0/us/) by Arthur Shlain.
