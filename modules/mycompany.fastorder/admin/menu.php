<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$aMenu = [
    [
        "parent_menu" => "global_menu_store",  // Привязываем к разделу "Магазин"
        "sort"        => 10,                 // Сортировка
        "text"        => Loc::getMessage("FASTORDER_MENU_TEXT"),
        "title"       => Loc::getMessage("FASTORDER_MENU_TITLE"),
        "url"         => "fastorder_orders.php?lang=" . LANGUAGE_ID,
        "icon"        => "sale_menu_icon_orders", // Иконка
        "page_icon"   => "sale_menu_icon_orders", // Иконка страницы
        "items_id"    => "menu_fastorder",      // Идентификатор пункта
    ]
];
return $aMenu;