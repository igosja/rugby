<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Championship
 * @package common\models\db
 *
 * @property int $id
 * @property int $bonus_loose
 * @property int $bonus_tries
 * @property int $federation_id
 * @property int $difference
 * @property int $division_id
 * @property int $draw
 * @property int $game
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
 * @property-read Federation $federation
 * @property-read Division $division
 * @property-read Season $season
 * @property-read Team $team
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['federation_id', 'division_id', 'place', 'season_id', 'team_id'], 'required'],
            [['bonus_loose', 'bonus_tries', 'point'], 'integer', 'min' => 0, 'max' => 99],
            [
                ['federation_id', 'difference', 'season_id', 'tries_against', 'tries_for'],
                'integer',
                'min' => 0,
                'max' => 999
            ],
            [['draw', 'loose', 'game', 'win'], 'integer', 'min' => 0, 'max' => 30],
            [['place'], 'integer', 'min' => 0, 'max' => 16],
            [['division_id'], 'integer', 'min' => 0, 'max' => 9],
            [['team_id'], 'integer', 'min' => 1],
            [['point_against', 'point_for'], 'integer', 'min' => 0, 'max' => 9999],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['division_id'], 'exist', 'targetRelation' => 'division'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDivision(): ActiveQuery
    {
        return $this->hasOne(Division::class, ['id' => 'division_id']);
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
