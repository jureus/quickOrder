<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
?>

<button class="fast-order-button" data-product-id="<?= $arResult["PRODUCT_ID"]?>">Быстрый заказ</button>

<div id="fastOrderModal" class="fastorder-modal">
    <div class="fastorder-modal-content" id="fastorder-modal-content">
        <span class="fastorder-modal-close">×</span>
        <form id="fastOrderForm">
            <div class="fastorder-form-group">
                <label for="fastorder-name">Имя:</label>
                <input type="text" id="fastorder-name" name="name" required>
            </div>
            <div class="fastorder-form-group">
                <label for="fastorder-phone">Телефон:</label>
                <input type="tel" id="fastorder-phone" name="phone" required>
            </div>
            <div class="fastorder-form-group">
                <label for="fastorder-email">Email (необязательно):</label>
                <input type="email" id="fastorder-email" name="email">
            </div>
            <div class="fastorder-form-group">
                <label for="fastorder-comment">Комментарий:</label>
                <textarea id="fastorder-comment" name="comment"></textarea>
            </div>
            <div class="fastorder-form-group">
                <button type="submit">Отправить заказ</button>
            </div>
        </form>
    </div>
</div>