<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Mood
 * @package common\models\db
 *
 * @property int $mood_id
 * @property string $mood_name
 */
class Mood extends AbstractActiveRecord
{
    const START_REST = 3;
    const START_SUPER = 3;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%mood}}';
    }
}
