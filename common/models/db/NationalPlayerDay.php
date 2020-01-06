<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class NationalPlayerDay
 * @package common\models\db
 *
 * @property int $national_player_day_id
 * @property int $national_player_day_day
 * @property int $national_player_day_national_id
 * @property int $national_player_day_player_id
 * @property int $national_player_day_team_id
 */
class NationalPlayerDay extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national_player_day}}';
    }
}
