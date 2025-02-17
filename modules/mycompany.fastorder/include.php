<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    "mycompany.fastorder",
    [
        "\\Mycompany\\Fastorder\\FastOrderTable" => "classes/general/FastOrderTable.php",
        "\\Mycompany\\Fastorder\\FastOrder" => "lib/FastOrder.php"
    ]
);