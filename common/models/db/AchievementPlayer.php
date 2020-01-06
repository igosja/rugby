<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class AchievementPlayer
 * @package common\models\db
 *
 * @property int $achievement_player_id
 * @property int $achievement_player_country_id
 * @property int $achievement_player_division_id
 * @property int $achievement_player_is_playoff
 * @property int $achievement_player_national_id
 * @property int $achievement_player_place
 * @property int $achievement_player_player_id
 * @property int $achievement_player_season_id
 * @property int $achievement_player_stage_id
 * @property int $achievement_player_team_id
 * @property int $achievement_player_tournament_type_id
 */
class AchievementPlayer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%achievement_player}}';
    }
}
