<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Name
 * @package common\models\db
 *
 * @property int $name_id
 * @property string $name_name
 */
class Name extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%name}}';
    }
}
