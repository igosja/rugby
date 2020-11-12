<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class WorldCup
 * @package common\models\db
 *
 * @property int $id
 * @property int $bonus_loose
 * @property int $bonus_tries
 * @property int $difference
 * @property int $division_id
 * @property int $draw
 * @property int $game
 * @property int $loose
 * @property int $national_id
 * @property int $national_type_id
 * @property int $place
 * @property int $point
 * @property int $point_against
 * @property int $point_for
 * @property int $season_id
 * @property int $tries_against
 * @property int $tries_for
 * @property int $win
 *
 * @property-read Division $division
 * @property-read National $national
 * @property-read NationalType $nationalType
 * @property-read Season $season
 */
class WorldCup extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%world_cup}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['division_id', 'national_id', 'national_type_id', 'place', 'season_id'], 'required'],
            [['national_type_id'], 'integer', 'min' => 0, 'max' => 1],
            [['bonus_loose', 'bonus_tries', 'point'], 'integer', 'min' => 0, 'max' => 99],
            [
                ['difference', 'national_id', 'season_id', 'tries_against', 'tries_for'],
                'integer',
                'min' => 0,
                'max' => 999
            ],
            [['draw', 'loose', 'game', 'win'], 'integer', 'min' => 0, 'max' => 10],
            [['place'], 'integer', 'min' => 0, 'max' => 10],
            [['division_id'], 'integer', 'min' => 0, 'max' => 9],
            [['point_against', 'point_for'], 'integer', 'min' => 0, 'max' => 9999],
            [['division_id'], 'exist', 'targetRelation' => 'division'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['national_type_id'], 'exist', 'targetRelation' => 'nationalType'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
        ];
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
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNationalType(): ActiveQuery
    {
        return $this->hasOne(NationalType::class, ['id' => 'national_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }
}
