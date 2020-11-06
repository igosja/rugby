<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LeagueDistribution
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $group
 * @property int $qualification_3
 * @property int $qualification_2
 * @property int $qualification_1
 * @property int $season_id
 *
 * @property-read Federation $federation
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
            [['federation_id', 'season_id'], 'required'],
            [['group', 'qualification_3', 'qualification_2', 'qualification_1'], 'integer', 'min' => 0, 'max' => 9],
            [['federation_id', 'season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
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
}
