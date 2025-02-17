<?php
B_PROLOG_INCLUDED === true || die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Mycompany\Fastorder\FastOrderTable;

Loc::loadMessages(__FILE__);

class mycompany_fastorder extends CModule
{
    var $MODULE_ID = 'mycompany.fastorder';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("FASTORDER_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("FASTORDER_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage("FASTORDER_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("FASTORDER_PARTNER_URI");
    }

    function DoInstall()
    {
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("FASTORDER_INSTALL_TITLE"),
            __DIR__ . "/step1.php"
        );
    }

    function DoUninstall()
    {
        global $APPLICATION;

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $this->UnInstallEvents();
        $this->UnInstallDB();
        $this->UnInstallFiles();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("FASTORDER_UNINSTALL_TITLE"),
            __DIR__ . "/unstep1.php"
        );
    }

    function InstallDB()
    {
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch(__DIR__ . "/db/mysql/install.sql");
        if (!$this->errors) {
            return true;
        } else {
            return $this->errors;
        }
    }

    function UnInstallDB()
    {
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch(__DIR__ . "/db/mysql/uninstall.sql");
        if (!$this->errors) {
            return true;
        } else {
            return $this->errors;
        }
    }

    function InstallEvents()
    {
        $eventType = new CEventType;
        $eventType->Add([
            'LID'         => 'ru',
            'EVENT_NAME'  => 'FASTORDER_NEW_ORDER',
            'NAME'        => 'Новый быстрый заказ',
            'DESCRIPTION' => '
                #ORDER_ID# - ID заказа
                #NAME# - Имя
                #PHONE# - Телефон
                #EMAIL# - Email
                #COMMENT# - Комментарий
                #PRODUCT_NAME# - Наименование товара
                #EMAIL_TO# - Email получателя
            ',
        ]);

        $eventMessage = new CEventMessage;
        $eventMessage->Add([
            'ACTIVE'     => 'Y',
            'EVENT_NAME' => 'FASTORDER_NEW_ORDER',
            'LID'        => ['s1'],
            'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
            'EMAIL_TO'   => '#EMAIL_TO#',
            'SUBJECT'    => 'Новый быстрый заказ ##ORDER_ID#',
            'BODY_TYPE'  => 'text',
            'MESSAGE'    => '
                Поступил новый быстрый заказ ##ORDER_ID#:

                Имя: #NAME#
                Телефон: #PHONE#
                Email: #EMAIL#
                Комментарий: #COMMENT#
                Товар: #PRODUCT_NAME#
            ',
        ]);

        return true;
    }

    function UnInstallEvents()
    {
        $eventType = new CEventType;
        $eventType->Delete('FASTORDER_NEW_ORDER');

        $eventMessage = new CEventMessage;
        $rsMess = CEventMessage::GetList($by = "site_id", $order = "desc", ["TYPE_ID" => "FASTORDER_NEW_ORDER"]);
        while ($arMess = $rsMess->GetNext()) {
            $eventMessage->Delete($arMess['ID']);
        }
        return true;
    }

	function InstallFiles()
	{
		CopyDirFiles(
			__DIR__ . "/admin",
			Application::getDocumentRoot() . "/bitrix/admin",
			true,
			true
		);

		CopyDirFiles(
			__DIR__ . "/components/fastorder",
			Application::getDocumentRoot() . "/bitrix/components/mycompany",
			true,
			true
		);
		return true;
	}

	function UnInstallFiles()
    {

        DeleteDirFiles(
            __DIR__ . "/admin",
            Application::getDocumentRoot() . "/bitrix/admin"
        );

         DeleteDirFilesEx("/bitrix/components/mycompany/fast.order");

        return true;
    }
}