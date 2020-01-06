<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class PlayerSpecial
 * @package common\models\db
 *
 * @property int $player_special_id
 * @property int $player_special_level
 * @property int $player_special_player_id
 * @property int $player_special_special_id
 *
 * @property Special $special
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
     * @return ActiveQuery
     */
    public function getSpecial(): ActiveQuery
    {
        return $this->hasOne(Special::class, ['special_id' => 'player_special_special_id']);
    }
}
