<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class FireReason
 * @package common\models\db
 *
 * @property int $fire_reason_id
 * @property string $fire_reason_text
 */
class FireReason extends AbstractActiveRecord
{
    const FIRE_REASON_SELF = 1;
    const FIRE_REASON_AUTO = 2;
    const FIRE_REASON_ABSENCE = 3;
    const FIRE_REASON_PENALTY = 4;
    const FIRE_REASON_EXTRA_TEAM = 5;
    const FIRE_REASON_NEW_SEASON = 6;
    const FIRE_REASON_VOTE = 7;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%fire_reason}}';
    }
}
