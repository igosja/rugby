<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\ErrorHelper;
use Throwable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;

/**
 * Class Vote
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property string $text
 * @property int $user_id
 * @property int $vote_status_id
 *
 * @property-read User $user
 * @property-read VoteAnswer[] $voteAnswers
 * @property-read VoteStatus $voteStatus
 */
class Vote extends AbstractActiveRecord
{
    /**
     * @var array $answers
     */
    public array $answers = [];

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%vote}}';
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
            [['text', 'user_id', 'vote_status_id'], 'required'],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['vote_status_id'], 'integer', 'min' => 1, 'max' => 9],
            [['user_id'], 'integer', 'min' => 1],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
            [['vote_status_id'], 'exist', 'targetRelation' => 'voteStatus'],
            [['answers'], 'each', 'rule' => ['trim']],
        ];
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete(): bool
    {
        foreach ($this->voteAnswers as $item) {
            $item->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * @return bool
     */
    public function saveVote(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->save();

            foreach ($this->voteAnswers as $item) {
                $item->delete();
            }

            foreach ($this->answers as $answer) {
                if (!$answer) {
                    continue;
                }

                $model = new VoteAnswer();
                $model->text = $answer;
                $model->vote_id = $this->id;
                $model->save();
            }

            if ($transaction) {
                $transaction->commit();
            }
        } catch (Throwable $e) {
            $transaction->rollBack();
            ErrorHelper::log($e);
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function prepareForm(): bool
    {
        for ($i = 0, $countAnswer = count($this->voteAnswers); $i < $countAnswer; $i++) {
            $this->answers[$i] = $this->voteAnswers[$i]->text;
        }

        return true;
    }

    /**
     * @return array
     */
    public function answers(): array
    {
        $result = [];
        $total = 0;
        foreach ($this->voteAnswers as $answer) {
            $count = count($answer->voteUsers);
            $result[] = [
                'answer' => $answer->text,
                'count' => $count,
            ];
            $total += $count;
        }
        foreach ($result as $key => $value) {
            $result[$key]['percent'] = $total ? round($result[$key]['count'] / $total * 100) : 0;
        }
        usort($result, static function ($a, $b) {
            return $b['count'] > $a['count'] ? 1 : 0;
        });
        return $result;
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
    public function getVoteAnswers(): ActiveQuery
    {
        return $this->hasMany(VoteAnswer::class, ['vote_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVoteStatus(): ActiveQuery
    {
        return $this->hasOne(VoteStatus::class, ['id' => 'vote_status_id']);
    }
}
