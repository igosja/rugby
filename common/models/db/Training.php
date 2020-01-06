<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Training
 * @package common\models\db
 *
 * @property int $training_id
 * @property int $training_percent
 * @property int $training_player_id
 * @property int $training_position_id
 * @property int $training_power
 * @property int $training_ready
 * @property int $training_season_id
 * @property int $training_special_id
 * @property int $training_team_id
 */
class Training extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%training}}';
    }
}
