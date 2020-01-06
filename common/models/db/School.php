<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class School
 * @package common\models\db
 *
 * @property int $school_id
 * @property int $school_day
 * @property int $school_position_id
 * @property int $school_ready
 * @property int $school_season_id
 * @property int $school_special_id
 * @property int $school_style_id
 * @property int $school_team_id
 * @property int $school_with_special
 * @property int $school_with_special_request
 * @property int $school_with_style
 * @property int $school_with_style_request
 */
class School extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%school}}';
    }
}
