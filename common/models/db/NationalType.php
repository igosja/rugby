<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class NationalType
 * @package common\models\db
 *
 * @property int $national_type_id
 * @property string $national_type_name
 */
class NationalType extends AbstractActiveRecord
{
    const MAIN = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national_type}}';
    }
}
