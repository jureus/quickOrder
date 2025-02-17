<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	"NAME" => Loc::getMessage("FASTORDER_COMPONENT_NAME"),
	"DESCRIPTION" => Loc::getMessage("FASTORDER_COMPONENT_DESCRIPTION"),
	"ICON" => "/images/icon.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "mycompany",
		"SORT" => 1000,
        "NAME" => "Мои компоненты"
    ],	
);