<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Division
 * @package common\models\db
 *
 * @property int $division_id
 * @property string $division_name
 */
class Division extends AbstractActiveRecord
{
    const D1 = 1;
    const D2 = 2;
    const D3 = 3;
    const D4 = 4;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%division}}';
    }
}
