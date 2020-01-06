<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Position
 * @package common\models\db
 *
 * @property int $position_id
 * @property string $position_name
 * @property string $position_text
 */
class Position extends AbstractActiveRecord
{
    const POS_01 = 1;
    const POS_02 = 2;
    const POS_03 = 3;
    const POS_04 = 4;
    const POS_05 = 5;
    const POS_06 = 6;
    const POS_07 = 7;
    const POS_08 = 8;
    const POS_09 = 9;
    const POS_10 = 10;
    const POS_11 = 11;
    const POS_12 = 12;
    const POS_13 = 13;
    const POS_14 = 14;
    const POS_15 = 15;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%position}}';
    }
}
