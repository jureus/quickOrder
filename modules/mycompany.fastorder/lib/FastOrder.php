<?php
namespace Mycompany\Fastorder;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class FastOrder
{
    public static function sendSMS(array $fields)
    {
        // Mock-функция для отправки SMS.
        // В реальном приложении здесь был бы код для работы с SMS-шлюзом.
        // Логируем данные, которые *должны* были быть отправлены.

        AddMessage2Log("Отправка SMS: " . print_r($fields, true));
    }
}

// Вынесено из ajax.php в отдельную функцию, чтобы не дублировать код
function fastorderSendSMS(array $fields){
    \Fastorder\FastOrder::sendSMS($fields);
}