<?php

/*
 * This file is part of PHP FunPay SMS Parser.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace AK\FunPay\SmsParser;

use AK\FunPay\SmsParser\Exceptions\YandexAccountInvalid;

class Message
{
    private $account = '';

    private $moneyAmount = 0.0;

    private $pin = '';

    public function __construct(string $account, float $moneyAmount, string $pin)
    {
        $this->assertYandexAccount($account);

        $this->account = $account;
        $this->moneyAmount = $moneyAmount;
        $this->pin = $pin;
    }

    public static function fromString(string $string): self
    {
        return (new Parser($string))->message();
    }

    public function account(): string
    {
        return $this->account;
    }

    public function moneyAmount(): float
    {
        return $this->moneyAmount;
    }

    public function pin(): string
    {
        return $this->pin;
    }

    private function assertYandexAccount(string $account): void
    {
        if (strlen($account) > 15 || strpos($account, '4100') !== 0) {
            throw new YandexAccountInvalid($account);
        }
    }
}
