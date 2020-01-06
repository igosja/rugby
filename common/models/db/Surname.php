<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Surname
 * @package common\models\db
 *
 * @property int $surname_id
 * @property string $surname_name
 *
 * @property Player[] $players
 */
class Surname extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%surname}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayers(): ActiveQuery
    {
        return $this->hasMany(Player::class, ['player_surname_id' => 'surname_id']);
    }
}
