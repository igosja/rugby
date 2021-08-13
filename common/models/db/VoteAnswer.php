<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class VoteAnswer
 * @package common\models\db
 *
 * @property int $id
 * @property string $text
 * @property int $vote_id
 *
 * @property-read Vote $vote
 * @property-read VoteUser[] $voteUsers
 */
class VoteAnswer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%vote_answer}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['text', 'vote_id'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string', 'max' => 255],
            [['vote_id'], 'integer', 'min' => 1],
            [['vote_id'], 'exist', 'targetRelation' => 'vote'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getVote(): ActiveQuery
    {
        return $this->hasOne(Vote::class, ['id' => 'vote_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVoteUsers(): ActiveQuery
    {
        return $this->hasMany(VoteUser::class, ['vote_answer_id' => 'id']);
    }
}
