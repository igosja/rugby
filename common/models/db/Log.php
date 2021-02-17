<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Log
 * @package common\models\db
 *
 * @property int $id
 * @property int $level
 * @property string $category
 * @property float $log_time
 * @property string $prefix
 * @property string $message
 */
class Log extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%log}}';
    }
}
