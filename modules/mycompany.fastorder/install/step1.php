<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?echo LANGUAGE_ID?>">
    <input type="hidden" name="id" value="fastorder">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    <?php echo CAdminMessage::ShowNote(Loc::getMessage("FASTORDER_INSTALL_SUCCESS")); ?>
    <input type="submit" name="" value="<?echo Loc::getMessage("MOD_BACK")?>">
<form>