<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Swiss
 * @package common\models\db
 *
 * @property int $id
 * @property int $guest
 * @property int $home
 * @property int $place
 * @property int $team_id
 *
 * @property-read Team $team
 */
class Swiss extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%swiss}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['team_id'], 'required'],
            [['guest', 'home'], 'integer', 'min' => 1, 'max' => 99],
            [['place', 'team_id'], 'integer', 'min' => 1, 'max' => 99],
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
