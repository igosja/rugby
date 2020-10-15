<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TeamVisitor
 * @package common\models\db
 *
 * @property int $id
 * @property int $team_id
 * @property int $visitor
 *
 * @property-read Team $team
 */
class TeamVisitor extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%team_visitor}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['team_id', 'visitor'], 'required'],
            [['visitor'], 'integer', 'min' => 1, 'max' => 999],
            [['team_id'], 'integer', 'min' => 1],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
