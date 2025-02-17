<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
        "PRODUCT_ID" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("FASTORDER_PRODUCT_ID_PARAM"),
            "TYPE" => "STRING",
            "DEFAULT" => '={$_REQUEST["PRODUCT_ID"]}',
        ),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),

	),
);