<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TransferComment
 * @package common\models\db
 *
 * @property int $id
 * @property int $check
 * @property int $date
 * @property int $transfer_id
 * @property string $text
 * @property int $user_id
 *
 * @property-read Transfer $transfer
 * @property-read User $user
 */
class TransferComment extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_comment}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['text', 'transfer_id', 'user_id'], 'required'],
            [['text'], 'string'],
            [['check', 'transfer_id', 'user_id'], 'integer', 'min' => 1],
            [['transfer_id'], 'exist', 'targetRelation' => 'transfer'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTransfer(): ActiveQuery
    {
        return $this->hasOne(Transfer::class, ['id' => 'transfer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
