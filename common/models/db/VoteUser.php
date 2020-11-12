<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\ErrorHelper;
use Exception;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class VoteUser
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $user_id
 * @property int $vote_answer_id
 *
 * @property-read User $user
 * @property-read VoteAnswer $voteAnswer
 */
class VoteUser extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%vote_user}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['user_id', 'vote_answer_id'], 'required'],
            [['user_id', 'vote_answer_id'], 'integer', 'min' => 1],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
            [['vote_answer_id'], 'exist', 'targetRelation' => 'voteAnswer'],
        ];
    }

    /**
     * @return bool
     */
    public function addAnswer(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }
        try {
            if (!$this->save()) {
                return false;
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
            return false;
        }
        return true;
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
    public function getVoteAnswer(): ActiveQuery
    {
        return $this->hasOne(VoteAnswer::class, ['id' => 'vote_answer_id']);
    }
}
