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

use AK\FunPay\SmsParser\Exceptions\MoneyAmountNotFound;
use AK\FunPay\SmsParser\Exceptions\PinNotFound;
use AK\FunPay\SmsParser\Exceptions\YandexAccountInvalid;
use AK\FunPay\SmsParser\Exceptions\YandexAccountNotFound;

class Parser
{
    private $account = '';

    private $moneyAmount = 0.0;

    private $pin = '';

    public function __construct(string $message)
    {
        $this->parseAccount($message);
        $this->parseMoneyAmount($message);
        $this->parsePin($message);
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

    private function parseAccount(string $message): void
    {
        if (preg_match('#(4100\d{8,})#', $message, $matches) !== 1) {
            throw new YandexAccountNotFound($message);
        }

        $account = $matches[1];

        $this->assertYandexAccount($account);

        $this->account = $account;
    }

    private function parseMoneyAmount(string $message): void
    {
        if (preg_match('#(\d+([.,]\d{1,2})?)р(уб)?\.#ui', $message, $matches) !== 1) {
            throw new MoneyAmountNotFound($message);
        }

        $this->moneyAmount = $this->moneyAmountFromString($matches[1]);
    }

    private function parsePin(string $message): void
    {
        if (preg_match('#(?<!\d)(\d{4})(?!\d)(?!\w)#u', $message, $matches) !== 1) {
            throw new PinNotFound($message);
        }

        $this->pin = $matches[1];
    }

    private function assertYandexAccount(string $account): void
    {
        if (strlen($account) > 15) {
            throw new YandexAccountInvalid($account);
        }
    }

    private function moneyAmountFromString(string $amount): float
    {
        $amount = str_replace(',', '.', $amount);

        return (float) $amount;
    }
}
