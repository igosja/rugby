<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Championship
 * @package common\models\db
 *
 * @property int $championship_id
 * @property int $championship_bonus_loose
 * @property int $championship_bonus_tries
 * @property int $championship_country_id
 * @property int $championship_difference
 * @property int $championship_division_id
 * @property int $championship_draw
 * @property int $championship_game
 * @property int $championship_loose
 * @property int $championship_place
 * @property int $championship_point
 * @property int $championship_point_against
 * @property int $championship_point_for
 * @property int $championship_season_id
 * @property int $championship_team_id
 * @property int $championship_tries_against
 * @property int $championship_tries_for
 * @property int $championship_win
 *
 * @property Country $country
 * @property Division $division
 * @property Team $team
 */
class Championship extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%championship}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['country_id' => 'championship_country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDivision(): ActiveQuery
    {
        return $this->hasOne(Division::class, ['division_id' => 'championship_division_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'championship_team_id']);
    }
}
