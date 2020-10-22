<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LeagueDistribution
 * @package common\models\db
 *
 * @property int $id
 * @property int $country_id
 * @property int $group
 * @property int $qualification_3
 * @property int $qualification_2
 * @property int $qualification_1
 * @property int $season_id
 *
 * @property-read Country $country
 * @property-read Season $season
 */
class LeagueDistribution extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%league_distribution}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id', 'season_id'], 'required'],
            [['group', 'qualification_3', 'qualification_2', 'qualification_1'], 'integer', 'min' => 0, 'max' => 9],
            [['country_id', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
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
}
