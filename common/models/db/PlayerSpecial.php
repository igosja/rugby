<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class PlayerSpecial
 * @package common\models\db
 *
 * @property int $id
 * @property int $level
 * @property int $player_id
 * @property int $special_id
 *
 * @property-read Lineup $lineup
 * @property-read Player $player
 * @property-read Special $special
 */
class PlayerSpecial extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%player_special}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['level', 'player_id', 'special_id'], 'required'],
            [['level'], 'integer', 'min' => 1, 'max' => 4],
            [['special_id'], 'integer', 'min' => 1, 'max' => 99],
            [['player_id'], 'integer', 'min' => 1],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['special_id'], 'exist', 'targetRelation' => 'special'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLineup(): ActiveQuery
    {
        return $this->hasMany(Lineup::class, ['player_id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSpecial(): ActiveQuery
    {
        return $this->hasOne(Special::class, ['id' => 'special_id']);
    }
}
