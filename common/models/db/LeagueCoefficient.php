<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LeagueCoefficient
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $loose
 * @property int $point
 * @property int $season_id
 * @property int $team_id
 * @property int $win
 *
 * @property-read Federation $federation
 * @property-read Season $season
 * @property-read Team $team
 */
class LeagueCoefficient extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%league_coefficient}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['federation_id', 'season_id', 'team_id'], 'required'],
            [['loose', 'point', 'win'], 'integer', 'min' => 0, 'max' => 99],
            [['federation_id', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['team_id'], 'integer', 'min' => 1],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
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
