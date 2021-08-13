<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class PlayerPosition
 * @package common\models\db
 *
 * @property int $id
 * @property int $player_id
 * @property int $position_id
 *
 * @property-read Player $player
 * @property-read Position $position
 */
class PlayerPosition extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%player_position}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['player_id', 'position_id'], 'required'],
            [['position_id'], 'integer', 'min' => 1, 'max' => 99],
            [['player_id'], 'integer', 'min' => 1],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
        ];
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
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }
}
