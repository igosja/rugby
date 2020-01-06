<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Conference
 * @package common\models\db
 *
 * @property int $conference_id
 * @property int $conference_bonus_loose
 * @property int $conference_bonus_tries
 * @property int $conference_difference
 * @property int $conference_draw
 * @property int $conference_game
 * @property int $conference_loose
 * @property int $conference_place
 * @property int $conference_point
 * @property int $conference_point_against
 * @property int $conference_point_for
 * @property int $conference_season_id
 * @property int $conference_team_id
 * @property int $conference_tries_against
 * @property int $conference_tries_for
 * @property int $conference_win
 *
 * @property Team $team
 */
class Conference extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%conference}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'conference_team_id']);
    }
}
