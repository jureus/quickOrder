<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Mycompany\Fastorder\FastOrderTable;

Loc::loadMessages(__FILE__);

Loader::includeModule('mycompany.fastorder');
Loader::includeModule('iblock');

$APPLICATION->SetTitle(Loc::getMessage("FASTORDER_ORDERS_TITLE"));

$sTableID = "tbl_fastorder_orders";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

// Фильтрация
$filterFields = [
    "find_id",
    "find_date_create_from",
    "find_date_create_to",
    "find_status",
];
$lAdmin->InitFilter($filterFields);

$filter = [];
if ($find_id) {
    $filter['ID'] = $find_id;
}
if ($find_date_create_from) {
    $filter['>=DATE_CREATE'] = $find_date_create_from;
}
if ($find_date_create_to) {
    $filter['<=DATE_CREATE'] = $find_date_create_to;
}
if ($find_status) {
    $filter['STATUS'] = $find_status;
}

// Обработка действий
if (($arID = $lAdmin->GroupAction()) && check_bitrix_sessid()) {
    foreach ($arID as $ID) {
        $ID = (int)$ID;
        if ($ID <= 0) {
            continue;
        }

        switch ($_REQUEST['action']) {
            case "mark_processed":
                FastOrderTable::update($ID, ['STATUS' => 'Y']);
                break;
        }
    }
}

// Получение списка заказов
$rsData = FastOrderTable::getList([
        'select' => ['*'], //  '*'
        'filter' => $filter,
        'order' => [$by => $order],
    ]);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("FASTORDER_ORDERS_NAV")));

// Заголовки таблицы
$lAdmin->AddHeaders([
    ["id" => "ID", "content" => "ID", "sort" => "id", "default" => true],
    ["id" => "DATE_CREATE", "content" => Loc::getMessage("FASTORDER_ORDERS_DATE_CREATE"), "sort" => "date_create", "default" => true],
    ["id" => "NAME", "content" => Loc::getMessage("FASTORDER_ORDERS_NAME"), "sort" => "name", "default" => true],
    ["id" => "PHONE", "content" => Loc::getMessage("FASTORDER_ORDERS_PHONE"), "default" => true],
    ["id" => "EMAIL", "content" => Loc::getMessage("FASTORDER_ORDERS_EMAIL"), "default" => true],
    ["id" => "COMMENT", "content" => Loc::getMessage("FASTORDER_ORDERS_COMMENT"), "default" => true],
    ["id" => "PRODUCT_NAME", "content" => Loc::getMessage("FASTORDER_ORDERS_PRODUCT"), "default" => true],
    ["id" => "STATUS", "content" => Loc::getMessage("FASTORDER_ORDERS_STATUS"), "sort" => "status", "default" => true],
]);


// Строки таблицы
while ($arRes = $rsData->NavNext(true, "f_")) {
    $row =& $lAdmin->AddRow($f_ID, $arRes);

    $row->AddViewField("DATE_CREATE", $f_DATE_CREATE);
    $row->AddViewField("NAME", $f_NAME);
    $row->AddViewField("PHONE", $f_PHONE);
    $row->AddViewField("EMAIL", $f_EMAIL);
    $row->AddViewField("COMMENT", $f_COMMENT);
    $row->AddViewField("PRODUCT_NAME", "<a href='".$f_PRODUCT_ID."' target='_blank'>".$f_PRODUCT_ID."</a>");

    $status = ($f_STATUS == 'Y') ? Loc::getMessage("FASTORDER_ORDERS_STATUS_PROCESSED") : Loc::getMessage("FASTORDER_ORDERS_STATUS_NEW");
    $row->AddViewField("STATUS", $status);


    // Действия
    $arActions = [];
    if ($f_STATUS != 'Y') {
        $arActions[] = [
            "ICON" => "edit",
            "TEXT" => Loc::getMessage("FASTORDER_ORDERS_MARK_PROCESSED"),
            "ACTION" => $lAdmin->ActionDoGroup($f_ID, "mark_processed"),
            "DEFAULT" => true,
        ];
    }


    $row->AddActions($arActions);
}


// Футер таблицы
$lAdmin->AddFooter([
    ["title" => Loc::getMessage("MAIN_ADMIN_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()],
    ["counter" => true, "title" => Loc::getMessage("MAIN_ADMIN_LIST_CHECKED"), "value" => "0"],
]);

// Групповые действия
$lAdmin->AddGroupActionTable([
    "mark_processed" => Loc::getMessage("FASTORDER_ORDERS_MARK_PROCESSED"),
]);


// Фильтр
$oFilter = new CAdminFilter(
    $sTableID . "_filter",
    [
        "ID",
        Loc::getMessage("FASTORDER_ORDERS_DATE_CREATE"),
        Loc::getMessage("FASTORDER_ORDERS_STATUS"),
    ]
);
?>
<?php
// ************************  ВЫВОД  ************************
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<form name="find_form" method="get" action="<?= $APPLICATION->GetCurPage() ?>">
    <?php $oFilter->Begin() ?>
    <tr>
        <td>ID:</td>
        <td>
            <input type="text" name="find_id" size="47" value="<?= htmlspecialcharsbx($find_id) ?>">
        </td>
    </tr>

    <tr>
        <td><?= Loc::getMessage("FASTORDER_ORDERS_DATE_CREATE") . ":" ?></td>
        <td>
            <?= CalendarPeriod("find_date_create_from", htmlspecialcharsbx($find_date_create_from), "find_date_create_to", htmlspecialcharsbx($find_date_create_to), "find_form", "Y") ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage("FASTORDER_ORDERS_STATUS") . ":" ?></td>
        <td>
            <select name="find_status">
                <option value=""><?= Loc::getMessage("MAIN_ALL") ?></option>
                <option value="N" <?= ($find_status == "N" ? "selected" : "") ?>><?= Loc::getMessage("FASTORDER_ORDERS_STATUS_NEW") ?></option>
                <option value="Y" <?= ($find_status == "Y" ? "selected" : "") ?>><?= Loc::getMessage("FASTORDER_ORDERS_STATUS_PROCESSED") ?></option>
            </select>
        </td>
    </tr>

    <?php
    $oFilter->Buttons(["table_id" => $sTableID, "url" => $APPLICATION->GetCurPage(), "form" => "find_form"]);
    $oFilter->End();
    ?>
</form>

<?php
$lAdmin->DisplayList();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");