<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class League
 * @package common\models\db
 *
 * @property int $id
 * @property int $bonus_loose
 * @property int $bonus_try
 * @property int $difference
 * @property int $draw
 * @property int $game
 * @property int $group
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
class League extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%league}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['group', 'place', 'season_id', 'team_id'], 'required'],
            [['place'], 'integer', 'min' => 1, 'max' => 4],
            [['draw', 'game', 'loose', 'win'], 'integer', 'min' => 0, 'max' => 6],
            [['group'], 'integer', 'min' => 1, 'max' => 8],
            [['bonus_loose', 'bonus_try', 'place'], 'integer', 'min' => 0, 'max' => 9],
            [['point', 'tries_against', 'tries_for'], 'integer', 'min' => 0, 'max' => 99],
            [['point_against', 'point_for', 'season_id'], 'integer', 'min' => 0, 'max' => 999],
            [['difference'], 'integer', 'min' => -999, 'max' => 999],
            [['team_id'], 'integer', 'min' => 1],
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
