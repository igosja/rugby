<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

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
