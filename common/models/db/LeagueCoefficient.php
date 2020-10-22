<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LeagueCoefficient
 * @package common\models\db
 *
 * @property int $id
 * @property int $country_id
 * @property int $loose
 * @property int $point
 * @property int $season_id
 * @property int $team_id
 * @property int $win
 *
 * @property-read Country $country
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
            [['country_id', 'season_id', 'team_id'], 'required'],
            [['loose', 'point', 'win'], 'integer', 'min' => 0, 'max' => 99],
            [['country_id', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['team_id'], 'integer', 'min' => 1],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
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
