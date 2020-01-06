<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Sex
 * @package common\models\db
 *
 * @property int $sex_id
 * @property string $sex_name
 */
class Sex extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%sex}}';
    }
}
