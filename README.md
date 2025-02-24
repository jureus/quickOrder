# Модуль "Быстрый заказ" для 1С-Битрикс (mycompany.fastorder)

## Описание

Модуль предоставляет функциональность "быстрого заказа" товара на сайте, работающем под управлением 1С-Битрикс.  Позволяет пользователям оформить заказ без добавления товара в корзину, через модальное окно с формой.  Включает административную часть для управления заказами.

**Скриншоты:**
*	https://disk.yandex.ru/i/33zWCB9K1Shdww
*	https://disk.yandex.ru/i/gjvSpuIf-Bf3tA
*	https://disk.yandex.ru/i/7JWsgCCVOekbcA

## Возможности

*   **Фронтенд:**
    *   Кнопка "Быстрый заказ" на странице товара.
    *   Модальное окно с формой заказа (имя, телефон, email, комментарий).
    *   Адаптивная верстка.
    *   Валидация формы на стороне клиента (JavaScript).
    *   Сообщение об успешной отправке заказа.
    *   Анимация открытия/закрытия модального окна.
    *   Автофокус на первом поле формы.
    *   Закрытие модального окна по клику вне окна и по нажатию Esc.

*   **Бэкенд:**
    *   Сохранение заказов в базу данных.
    *   Отправка уведомлений администратору сайта по email.
    *   Административный интерфейс:
        *   Просмотр списка заказов.
        *   Фильтрация заказов по дате и статусу.
        *   Пометка заказов как обработанных.
    *   Mock-функция отправки SMS-уведомлений (запись в лог).

## Технические требования

*   1С-Битрикс: редакции "Бизнес" или "Малый бизнес" (проверено на демо-установке).
*   PHP: 7.4+ (желательно 8.0+).
*   D7.

## Установка

1.  **Скопируйте папку модуля:**  Скопируйте папку `mycompany.fastorder` в `/bitrix/modules/` (или `local/modules/`, если вы разрабатываете локально).  Убедитесь, что структура папок сохранена:

    ```
    mycompany.fastorder/
    ├── admin/
    │   ├── fastorder_orders.php
    │   └── menu.php
    ├── classes/
    │   └── general/
    │       └── FastOrderTable.php
    ├── components/
    │   └── mycompany/
    │       └── fast.order/
    │           ├── .description.php
    │           ├── .parameters.php
    │           ├── component.php
    │           ├── lang/
    │           │    └── ru/
    │           ├── templates/
    │           │   └── .default/
    │           │       ├── script.js
    │           │       ├── style.css
    │           │       └── template.php
    │           └── ajax.php
    ├── include.php
    ├── install/
    │   ├── index.php
    │   ├── db/
    │   │   └── mysql/
    │   │       ├── install.sql
    │   │       └── uninstall.sql
    │   └── step1.php
        └── unstep1.php
    ├── lang/
    │   └── ru/
    │      ├── admin/
    │      │    ├── fastorder_orders.php
    │      │    └── menu.php
    │      ├── install/
    │      │    ├── index.php
    │      │     └── step1.php
            │      └── unstep1.php
    │      └── options.php
    ├── lib/
    │   └── FastOrder.php
    └── options.php
    ```

2.  **Установите модуль:**
    *   Перейдите в *Администрирование -> Marketplace -> Установленные решения*.
    *   Найдите модуль "Быстрый заказ" в списке и нажмите "Установить".
    *   Следуйте инструкциям мастера установки.

3. **Разместите компонент на странице товара:**

*   Откройте страницу детального просмотра товара в режиме редактирования.
*   Добавьте следующий код в нужное место шаблона (обычно рядом с кнопкой "В корзину"):
    ```php
        <?$APPLICATION->IncludeComponent(
            "mycompany:fast.order",
            "",
            Array(
                "PRODUCT_ID" => $arResult["ID"], //  ID товара
            ),
            false
        );?>
    ```

## Использование

1.  **Фронтенд:**
    *   На странице товара появится кнопка "Быстрый заказ".
    *   При нажатии на кнопку открывается модальное окно с формой.
    *   Заполните форму и нажмите "Отправить заказ".
    *   При успешной отправке появится сообщение об успехе.

2.  **Админка:**
    *   Перейдите в *Магазин -> Быстрые заказы*.
    *   Вы увидите список оформленных быстрых заказов.
    *   Можно фильтровать заказы по дате и статусу, а также помечать заказы как обработанные.

## Временное решение (ВАЖНО!)

В текущей версии модуля, в целях ускорения разработки, **вместо ID товара в базу данных сохраняется URL страницы**, с которой был сделан заказ. Это **временное решение ("костыль")**.  В поле `PRODUCT_ID` таблицы `fastorder_orders` хранится строка (varchar), а не число.

**В будущем это необходимо исправить:**

1.  Изменить тип поля `PRODUCT_ID` обратно на `int(11)`.
2.  Добавить в форму заказа (и в `script.js`) передачу *настоящего* ID товара.
3.  Исправить сохранение заказа в `ajax.php`.
4.  Исправить отображение товара в административной части (получать название товара по ID).

## Дополнительная информация
* В модуле реализована mock функция, имитирующая отправку SMS.
* Реализована анимация открытия/закрытия модального окна.
*

## Автор
jureus