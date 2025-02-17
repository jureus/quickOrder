<?php
namespace Mycompany\Fastorder;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class FastOrderTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'fastorder_orders';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('FASTORDER_ENTITY_ID_FIELD')
            ]),
            new Entity\DatetimeField('DATE_CREATE', [
                'default_value' => new Type\DateTime(),
                'title' => Loc::getMessage('FASTORDER_ENTITY_DATE_CREATE_FIELD')
            ]),
            new Entity\StringField('NAME', [
                'required' => true,
                'title' => Loc::getMessage('FASTORDER_ENTITY_NAME_FIELD')
            ]),
            new Entity\StringField('PHONE', [
                'required' => true,
                'title' => Loc::getMessage('FASTORDER_ENTITY_PHONE_FIELD')
            ]),
            new Entity\StringField('EMAIL', [
                'title' => Loc::getMessage('FASTORDER_ENTITY_EMAIL_FIELD')
            ]),
            new Entity\TextField('COMMENT', [
                'title' => Loc::getMessage('FASTORDER_ENTITY_COMMENT_FIELD')
            ]),
            new Entity\StringField('PRODUCT_ID', [
                'required' => true,
                'title' => 'ID товара'
            ]),
            // new Entity\ReferenceField(
                // 'PRODUCT',
                // '\Bitrix\Iblock\ElementTable',
                // ['=this.PRODUCT_ID' => 'ref.ID']
            // ),
            new Entity\StringField('STATUS', [
                'default_value' => 'N',
                'title' => Loc::getMessage('FASTORDER_ENTITY_STATUS_FIELD')
            ]),
        ];
    }
}