<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class GameVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $game_id
 * @property int $rating
 * @property int $user_id
 *
 * @property-read Game $game
 * @property-read User $user
 */
class GameVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%game_vote}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['game_id', 'rating', 'user_id'], 'required'],
            [['game_id', 'user_id'], 'integer', 'min' => 1],
            [['rating'], 'integer', 'min' => -1, 'max' => 1],
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
