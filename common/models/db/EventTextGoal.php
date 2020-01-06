<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class EventTextGoal
 * @package common\models\db
 *
 * @property int $event_text_goal_id
 * @property string $event_text_goal_text
 */
class EventTextGoal extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%event_text_goal}}';
    }
}
