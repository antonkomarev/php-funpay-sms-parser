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
use AK\FunPay\SmsParser\Exceptions\YandexAccountNotFound;

class Parser
{
    private $message;

    public function __construct(string $string)
    {
        $this->message = new Message(
            $this->parseAccount($string),
            $this->parseMoneyAmount($string),
            $this->parsePin($string)
        );
    }

    public function message(): Message
    {
        return $this->message;
    }

    private function parseAccount(string $message): string
    {
        if (preg_match('#(4100\d{8,})#', $message, $matches) !== 1) {
            throw new YandexAccountNotFound($message);
        }

        return $matches[1];
    }

    private function parseMoneyAmount(string $message): float
    {
        if (preg_match('#(\d+([.,]\d{1,2})?)р(уб)?\.#ui', $message, $matches) !== 1) {
            throw new MoneyAmountNotFound($message);
        }

        return $this->moneyAmountFromString($matches[1]);
    }

    private function parsePin(string $message): string
    {
        if (preg_match('#(?<!\d)(\d{4})(?!\d)(?!\w)#u', $message, $matches) !== 1) {
            throw new PinNotFound($message);
        }

        return $matches[1];
    }

    private function moneyAmountFromString(string $amount): float
    {
        $amount = str_replace(',', '.', $amount);

        return (float) $amount;
    }
}
