<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Vote
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $date
 * @property string $text
 * @property int $user_id
 * @property int $vote_status_id
 *
 * @property-read Federation $federation
 * @property-read User $user
 * @property-read VoteStatus $voteStatus
 */
class Vote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%vote}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['text', 'user_id', 'vote_status_id'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['vote_status_id'], 'integer', 'min' => 1, 'max' => 9],
            [['federation_id'], 'integer', 'min' => 1, 'max' => 999],
            [['user_id'], 'integer', 'min' => 1],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
            [['vote_status_id'], 'exist', 'targetRelation' => 'voteStatus'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVoteStatus(): ActiveQuery
    {
        return $this->hasOne(VoteStatus::class, ['id' => 'vote_status_id']);
    }
}
