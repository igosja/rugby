<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Surname
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayers(): ActiveQuery
    {
        return $this->hasMany(Player::class, ['surname_id' => 'id']);
    }
}
