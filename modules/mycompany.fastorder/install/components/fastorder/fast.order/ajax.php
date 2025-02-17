<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Mycompany\Fastorder\FastOrderTable; // Используем наш ORM
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$request = Context::getCurrent()->getRequest();
$response = [];

if ($request->isPost() && check_bitrix_sessid() && $request->get('action') === 'addOrder') {
    Loader::includeModule('mycompany.fastorder');

    $productId = $request->get('productId'); // Получаем productId
    $name = $request->get('name');
    $phone = $request->get('phone');
    $email = $request->get('email');
    $comment = $request->get('comment');

    //Валидация на стороне сервера
    if (strlen($name) < 2) {
        $response = ["status" => "error", "message" => "Имя должно содержать минимум 2 символа"];
    } elseif (empty($phone)) {
        $response = ["status" => "error", "message" => "Телефон обязателен"];
    } elseif ($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ["status" => "error", "message" => "Неверный формат email"];
    } else {
        $result = FastOrderTable::add([
            'NAME' => $name,
            'PHONE' => $phone,
            'EMAIL' => $email,
            'COMMENT' => $comment,
            'PRODUCT_ID' => $productId, //Сохраняем ID продукта
        ]);

        if ($result->isSuccess()) {
            $rsSites = CSite::GetList($by = "sort", $order = "desc", []);
            if ($arSite = $rsSites->Fetch()) {
                $emailTo = $arSite['EMAIL'];
            }

            // $productName = '';
            // if (Loader::includeModule('iblock')) {
                // $product = \Bitrix\Iblock\ElementTable::getRow([
                    // 'select' => ['NAME'],
                    // 'filter' => ['ID' => $productId]
                // ]);
                // if ($product) {
                    // $productName = $product['NAME'];
                // }
            // }

            $fields = [
                'ORDER_ID' => $result->getId(),
                'NAME' => $name,
                'PHONE' => $phone,
                'EMAIL' => $email,
                'COMMENT' => $comment,
                // "PRODUCT_NAME" => $productName,
                "EMAIL_TO" => $emailTo
            ];

            Event::send([
                "EVENT_NAME" => "FASTORDER_NEW_ORDER",
                "LID" => SITE_ID,
                "C_FIELDS" => $fields,
            ]);

            \Mycompany\Fastorder\FastOrder::sendSMS($fields); // mock SMS

            $response = ['status' => 'success', 'message' => Loc::getMessage('FASTORDER_ORDER_SUCCESS')];
        } else {
            $errors = $result->getErrorMessages();
            $response = ['status' => 'error', 'message' => implode(', ', $errors)];
        }
    }
} else {
     $response = ["status" => "error", "message" => "Некорректный запрос"];
}

header('Content-Type: application/json');
echo Json::encode($response);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
die();