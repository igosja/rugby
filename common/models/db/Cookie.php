<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Cookie
 * @package common\models\db
 *
 * @property int $cookie_id
 * @property int $cookie_child_id
 * @property int $cookie_count
 * @property int $cookie_date
 * @property int $cookie_parent_id
 */
class Cookie extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%cookie}}';
    }
}
