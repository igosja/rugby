<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class EventType
 * @package common\models\db
 *
 * @property int $event_type_id
 * @property string $event_type_text
 */
class EventType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%event_type}}';
    }
}
