<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class TransferApplication
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $deal_reason_id
 * @property bool $is_only_one
 * @property int $price
 * @property int $team_id
 * @property int $transfer_id
 * @property int $user_id
 *
 * @property-read DealReason $dealReason
 * @property-read Team $team
 * @property-read Transfer $transfer
 * @property-read User $user
 */
class TransferApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_application}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['is_only_one', 'price', 'team_id', 'transfer_id', 'user_id'], 'required'],
            [['is_only_one'], 'boolean'],
            [['deal_reason_id'], 'integer', 'min' => 1, 'max' => 99],
            [['price', 'team_id', 'transfer_id', 'user_id'], 'integer', 'min' => 1],
            [['deal_reason_id'], 'exist', 'targetRelation' => 'dealReason'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['transfer_id'], 'exist', 'targetRelation' => 'transfer'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDealReason(): ActiveQuery
    {
        return $this->hasOne(DealReason::class, ['id' => 'deal_reason_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
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
