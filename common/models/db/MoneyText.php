<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class MoneyText
 * @package common\models\db
 *
 * @property int $money_text_id
 * @property string $money_text_text
 */
class MoneyText extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%money_text}}';
    }
}
