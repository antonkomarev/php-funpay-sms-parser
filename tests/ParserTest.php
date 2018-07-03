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

use AK\FunPay\SmsParser\Exceptions\MoneyAmountNotFound;
use AK\FunPay\SmsParser\Exceptions\PinNotFound;
use AK\FunPay\SmsParser\Exceptions\YandexAccountInvalid;
use AK\FunPay\SmsParser\Exceptions\YandexAccountNotFound;
use AK\FunPay\SmsParser\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /** @test */
    public function it_can_get_account(): void
    {
        $message = 'Пароль: 0823<br />Спишется 100,85р.<br />Перевод на счет 410017080996934';

        $parser = new Parser($message);

        $this->assertSame('410017080996934', $parser->account());
    }

    /** @test */
    public function it_can_get_money_amount(): void
    {
        $message = 'Пароль: 0823<br />Спишется 100,85р.<br />Перевод на счет 410017080996934';

        $parser = new Parser($message);

        $this->assertSame(100.85, $parser->moneyAmount());
    }

    /** @test */
    public function it_can_get_pin(): void
    {
        $message = 'Пароль: 0823<br />Спишется 100,85р.<br />Перевод на счет 410017080996934';

        $parser = new Parser($message);

        $this->assertSame('0823', $parser->pin());
    }

    /** @test */
    public function it_can_parse_account_if_message_reordered(): void
    {
        $message = 'Перевод на счет 410017080996934<br />Спишется 100,85р.<br />Пароль: 0823';

        $parser = new Parser($message);

        $this->assertSame('410017080996934', $parser->account());
    }

    /** @test */
    public function it_can_parse_money_amount_if_message_reordered(): void
    {
        $message = 'Пароль: 0823<br />Перевод на счет 410017080996934<br />Спишется 100,85р.';

        $parser = new Parser($message);

        $this->assertSame(100.85, $parser->moneyAmount());
    }

    /** @test */
    public function it_can_parse_pin_if_message_reordered(): void
    {
        $message = 'Спишется 100,85р.<br />Перевод на счет 410017080996934<br />Пароль: 0823';

        $parser = new Parser($message);

        $this->assertSame('0823', $parser->pin());
    }

    /** @test */
    public function it_can_parse_reordered_message_without_words(): void
    {
        $message = '410017080996934 100,85р. 0823';

        $parser = new Parser($message);

        $this->assertSame('410017080996934', $parser->account());
        $this->assertSame(100.85, $parser->moneyAmount());
        $this->assertSame('0823', $parser->pin());
    }

    /** @test */
    public function it_can_parse_message_on_foreign_language(): void
    {
        $message = 'We will charge 100,85р. from Yandex.Money wallet #410017080996934. Send confirmation code 0823 to approve this operation.';

        $parser = new Parser($message);

        $this->assertSame('410017080996934', $parser->account());
        $this->assertSame(100.85, $parser->moneyAmount());
        $this->assertSame('0823', $parser->pin());
    }

    /** @test */
    public function it_can_parse_integer_money_amount(): void
    {
        $message = 'Пароль: 0823<br />Спишется 100р.<br />Перевод на счет 410017080996934';

        $parser = new Parser($message);

        $this->assertSame(100.0, $parser->moneyAmount());
    }

    /** @test */
    public function it_can_parse_integer_money_amount_same_length_with_pin(): void
    {
        $message = 'Пароль: 0823<br />Спишется 1000р.<br />Перевод на счет 410017080996934';

        $parser = new Parser($message);

        $this->assertSame(1000.0, $parser->moneyAmount());
    }

    /** @test */
    public function it_can_parse_money_amount_with_alter_currency(): void
    {
        $message = 'Пароль: 0823<br />Спишется 100,85руб.<br />Перевод на счет 410017080996934';

        $parser = new Parser($message);

        $this->assertSame(100.85, $parser->moneyAmount());
    }

    /** @test */
    public function it_can_parse_upper_cased_money_amount(): void
    {
        $message = 'Пароль: 0823<br />Спишется 100,85Р.<br />Перевод на счет 410017080996934';

        $parser = new Parser($message);

        $this->assertSame(100.85, $parser->moneyAmount());
    }

    /** @test */
    public function it_throw_exception_if_account_not_found(): void
    {
        $message = 'Пароль: 0823<br />Спишется 1000р.';

        $this->expectException(YandexAccountNotFound::class);

        new Parser($message);
    }

    /** @test */
    public function it_throw_exception_if_account_starting_with_4100_not_found(): void
    {
        $message = 'Пароль: 0823<br />Спишется 100,85р.<br />Перевод на счет 110017080996934';

        $this->expectException(YandexAccountNotFound::class);

        new Parser($message);
    }

    /** @test */
    public function it_throw_exception_if_account_length_lower_than_expected(): void
    {
        $message = 'Пароль: 0823<br />Спишется 1000р.<br />Перевод на счет 4100170';

        $this->expectException(YandexAccountNotFound::class);

        new Parser($message);
    }

    /** @test */
    public function it_throw_exception_if_account_length_greater_than_expected(): void
    {
        $message = 'Пароль: 0823<br />Спишется 1000р.<br />Перевод на счет 4100170809969345';

        $this->expectException(YandexAccountInvalid::class);

        new Parser($message);
    }

    /** @test */
    public function it_throw_exception_if_money_amount_not_found(): void
    {
        $message = 'Пароль: 0823<br />Перевод на счет 410017080996934';

        $this->expectException(MoneyAmountNotFound::class);

        new Parser($message);
    }

    /** @test */
    public function it_throw_exception_if_pin_not_found(): void
    {
        $message = 'Спишется 1000р.<br />Перевод на счет 410017080996934';

        $this->expectException(PinNotFound::class);

        new Parser($message);
    }
}
