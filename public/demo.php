<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use AK\FunPay\SmsParser\Exceptions\MoneyAmountNotFound;
use AK\FunPay\SmsParser\Exceptions\PinNotFound;
use AK\FunPay\SmsParser\Exceptions\YandexAccountNotFound;
use AK\FunPay\SmsParser\Parser;

$message = '
    Пароль: 0823<br />
    Спишется 100,85р.<br />
    Перевод на счет 410017080996934
';

//$message = '
//    Для перевода на Яндекс Кошелёк 410017080996934
//    суммы 100,85руб. отправьте Пин 0823.<br />
//';

//$message = '
//    We will charge 100.85р. from Yandex.Money wallet #410017080996.
//    Send confirmation code 0823 to approve this operation
//';

try {
    $parser = new Parser($message);
    $account = $parser->account();
    $moneyAmount = $parser->moneyAmount();
    $pin = $parser->pin();

    echo "Номер кошелька: {$account}\n";
    echo "Сумма для перевода: {$moneyAmount}\n";
    echo "Пин-код: {$pin}\n";

} catch (YandexAccountNotFound $exception) {
    echo 'В SMS сообщении отсутствует номер кошелька Яндекс Деньги.';
} catch (MoneyAmountNotFound $exception) {
    echo 'В SMS сообщении отсутствует сумма для перевода.';
} catch (PinNotFound $exception) {
    echo 'В SMS сообщении отсутствует пароль.';
}
