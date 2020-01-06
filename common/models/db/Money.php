<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Money
 * @package common\models\db
 *
 * @property int $money_id
 * @property int $money_date
 * @property int $money_money_text_id
 * @property int $money_user_id
 * @property float $money_value
 * @property float $money_value_after
 * @property float $money_value_before
 */
class Money extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%money}}';
    }
}
