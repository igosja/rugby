<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class OffSeason
 * @package common\models\db
 *
 * @property int $off_season_id
 * @property int $off_season_bonus_loose
 * @property int $off_season_bonus_tries
 * @property int $off_season_difference
 * @property int $off_season_draw
 * @property int $off_season_game
 * @property int $off_season_guest
 * @property int $off_season_home
 * @property int $off_season_loose
 * @property int $off_season_place
 * @property int $off_season_point
 * @property int $off_season_point_against
 * @property int $off_season_point_for
 * @property int $off_season_season_id
 * @property int $off_season_team_id
 * @property int $off_season_tries_against
 * @property int $off_season_tries_for
 * @property int $off_season_win
 *
 * @property Team $team
 */
class OffSeason extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%off_season}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'off_season_team_id'])->cache();
    }
}
