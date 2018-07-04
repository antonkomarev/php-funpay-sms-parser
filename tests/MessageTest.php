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

namespace AK\Tests\FunPay\SmsParser;

use AK\FunPay\SmsParser\Exceptions\YandexAccountInvalid;
use AK\FunPay\SmsParser\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @test */
    public function it_can_get_account(): void
    {
        $account = '410017080996934';
        $moneyAmount = 100.85;
        $pin = '0823';

        $message = new Message($account, $moneyAmount, $pin);

        $this->assertSame('410017080996934', $message->account());
    }

    /** @test */
    public function it_can_get_money_amount(): void
    {
        $account = '410017080996934';
        $moneyAmount = 100.85;
        $pin = '0823';

        $message = new Message($account, $moneyAmount, $pin);

        $this->assertSame(100.85, $message->moneyAmount());
    }

    /** @test */
    public function it_can_get_pin(): void
    {
        $account = '410017080996934';
        $moneyAmount = 100.85;
        $pin = '0823';

        $message = new Message($account, $moneyAmount, $pin);

        $this->assertSame('0823', $message->pin());
    }

    /** @test */
    public function it_can_set_integer_money_amount(): void
    {
        $account = '410017080996934';
        $moneyAmount = 100;
        $pin = '0823';

        $message = new Message($account, $moneyAmount, $pin);

        $this->assertSame(100.0, $message->moneyAmount());
    }

    /** @test */
    public function it_throw_exception_if_account_length_greater_than_expected(): void
    {
        $account = '4100170809969345';
        $moneyAmount = 100;
        $pin = '0823';

        $this->expectException(YandexAccountInvalid::class);

        new Message($account, $moneyAmount, $pin);
    }

    /** @test */
    public function it_throw_exception_if_account_starts_not_from_4100(): void
    {
        $account = '200017080996934';
        $moneyAmount = 100;
        $pin = '0823';

        $this->expectException(YandexAccountInvalid::class);

        new Message($account, $moneyAmount, $pin);
    }
}
