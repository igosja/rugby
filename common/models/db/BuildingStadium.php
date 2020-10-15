<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class BuildingStadium
 * @package common\models\db
 *
 * @property int $id
 * @property int $capacity
 * @property int $construction_type_id
 * @property int $date
 * @property int $day
 * @property int $ready
 * @property int $team_id
 *
 * @property-read ConstructionType $constructionType
 * @property-read Team $team
 */
class BuildingStadium extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building_stadium}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['capacity', 'construction_type_id', 'day', 'team_id'], 'required'],
            [['construction_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['capacity'], 'integer', 'min' => 0, 'max' => 99999],
            [['day'], 'integer', 'min' => 0, 'max' => 99],
            [['date', 'ready', 'team_id'], 'integer', 'min' => 0],
            [['construction_type_id'], 'exist', 'targetRelation' => 'constructionType'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getConstructionType(): ActiveQuery
    {
        return $this->hasOne(ConstructionType::class, ['id' => 'construction_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
