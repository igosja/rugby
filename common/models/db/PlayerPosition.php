<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class PlayerPosition
 * @package common\models\db
 *
 * @property int $player_position_player_id
 * @property int $player_position_position_id
 *
 * @property Position $position
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
     * @return ActiveQuery
     */
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['position_id' => 'player_position_position_id']);
    }
}
