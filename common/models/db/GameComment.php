<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class GameComment
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
 * @property int $date
 * @property int $game_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read Game $game
 * @property-read User $user
 */
class GameComment extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%game_comment}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['game_id', 'text', 'user_id'], 'required'],
            [['check', 'game_id', 'user_id'], 'integer', 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['game_id'], 'exist', 'targetRelation' => 'game'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGame(): ActiveQuery
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
