<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * Class Message
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $from_user_id
 * @property int $read
 * @property string $text
 * @property int $to_user_id
 *
 * @property-read User $fromUser
 * @property-read User $toUser
 */
class Message extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%message}}';
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
            [['from_user_id', 'text', 'to_user_id'], 'required'],
            [['text'], 'string'],
            [['from_user_id', 'read', 'to_user_id'], 'integer', 'min' => 1],
            [['from_user_id'], 'exist', 'targetRelation' => 'fromUser'],
            [['to_user_id'], 'exist', 'targetRelation' => 'toUser'],
        ];
    }

    /**
     * @param int $userId
     * @return bool
     * @throws Exception
     */
    public function addMessage(int $userId): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;

        if (!$user->date_confirm) {
            return false;
        }

        $inBlacklistOwner = Blacklist::find()
            ->where(['owner_user_id' => $user->id, 'blocked_user_id' => $userId])
            ->count();
        if ($inBlacklistOwner) {
            return false;
        }

        $inBlacklistBlocked = Blacklist::find()
            ->where(['owner_user_id' => $userId, 'blocked_user_id' => $user->id])
            ->count();
        if ($inBlacklistBlocked) {
            return false;
        }

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $this->from_user_id = Yii::$app->user->id;
        $this->to_user_id = $userId;

        if (!$this->save()) {
            return false;
        }

        return true;
    }

    /**
     * @return ActiveQuery
     */
    public function getFromUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'from_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getToUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'to_user_id']);
    }
}
