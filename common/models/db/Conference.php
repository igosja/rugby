<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Conference
 * @package common\models\db
 *
 * @property int $id
 * @property int $bonus_loose
 * @property int $bonus_try
 * @property int $difference
 * @property int $draw
 * @property int $game
 * @property int $guest
 * @property int $home
 * @property int $loose
 * @property int $place
 * @property int $point
 * @property int $point_against
 * @property int $point_for
 * @property int $season_id
 * @property int $team_id
 * @property int $tries_against
 * @property int $tries_for
 * @property int $win
 *
 * @property-read Season $season
 * @property-read Team $team
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['place', 'season_id', 'team_id'], 'required'],
            [['bonus_loose', 'bonus_try', 'point'], 'integer', 'min' => 0, 'max' => 99],
            [['difference', 'season_id', 'tries_against', 'tries_for'], 'integer', 'min' => 0, 'max' => 999],
            [['draw', 'guest', 'hoem', 'loose', 'game', 'win'], 'integer', 'min' => 0, 'max' => 30],
            [['place'], 'integer', 'min' => 0, 'max' => 16],
            [['team_id'], 'integer', 'min' => 0],
            [['point_against', 'point_for'], 'integer', 'min' => 0, 'max' => 9999],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
