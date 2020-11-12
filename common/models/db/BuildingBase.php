<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class BuildingBase
 * @package common\models\db
 *
 * @property int $id
 * @property int $building_id
 * @property int $construction_type_id
 * @property int $date
 * @property int $day
 * @property int $ready
 * @property int $team_id
 *
 * @property-read Building $building
 * @property-read ConstructionType $constructionType
 * @property-read Team $team
 */
class BuildingBase extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building_base}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['building_id', 'construction_type_id', 'day', 'team_id'], 'required'],
            [['building_id', 'construction_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [['day'], 'integer', 'min' => 0, 'max' => 99],
            [['date', 'ready', 'team_id'], 'min' => 0, 'integer'],
            [['building_id'], 'exist', 'targetRelation' => 'building'],
            [['construction_type_id'], 'exist', 'targetRelation' => 'constructionType'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBuilding(): ActiveQuery
    {
        return $this->hasOne(Building::class, ['id' => 'building_id']);
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
