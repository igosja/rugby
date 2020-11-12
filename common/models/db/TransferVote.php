<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TransferVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $transfer_id
 * @property int $rating
 * @property int $user_id
 *
 * @property-read Transfer $transfer
 * @property-read User $user
 */
class TransferVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_vote}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['transfer_id', 'rating', 'user_id'], 'required'],
            [['rating'], 'integer', 'min' => -10, 'max' => 10],
            [['transfer_id', 'user_id'], 'integer', 'min' => 1],
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
