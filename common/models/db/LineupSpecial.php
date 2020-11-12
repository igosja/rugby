<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LineupSpecial
 * @package common\models\db
 *
 * @property int $id
 * @property int $level
 * @property int $lineup_id
 * @property int $special_id
 *
 * @property-read Lineup $lineup
 * @property-read Special $special
 */
class LineupSpecial extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%lineup_special}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['level', 'lineup_id', 'special_id'], 'required'],
            [['level'], 'integer', 'min' => 1, 'max' => 4],
            [['special_id'], 'integer', 'min' => 1, 'max' => 99],
            [['lineup_id'], 'integer', 'min' => 1],
            [['lineup_id'], 'exist', 'targetRelation' => 'lineup'],
            [['special_id'], 'exist', 'targetRelation' => 'special'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLineup(): ActiveQuery
    {
        return $this->hasOne(Lineup::class, ['id' => 'lineup_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSpecial(): ActiveQuery
    {
        return $this->hasOne(Special::class, ['id' => 'special_id']);
    }
}
