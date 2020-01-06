<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Special
 * @package common\models\db
 *
 * @property int $special_id
 * @property string $special_name
 * @property string $special_text
 */
class Special extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%special}}';
    }
}
