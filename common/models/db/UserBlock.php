<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class UserBlock
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $user_block_reason_id
 * @property int $user_block_type_id
 * @property int $user_id
 *
 * @property-read UserBlockReason $userBlockReason
 * @property-read UserBlockType $userBlockType
 * @property-read User $user
 */
class UserBlock extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_block}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['user_block_reason_id', 'user_block_type_id', 'user_id'], 'required'],
            [['user_block_type_id'], 'integer', 'min' => 1, 'max' => 9],
            [['user_block_reason_id'], 'integer', 'min' => 1, 'max' => 99],
            [['user_id'], 'integer', 'min' => 1],
            [['user_block_reason_id'], 'exist', 'targetRelation' => 'userBlockReason'],
            [['user_block_type_id'], 'exist', 'targetRelation' => 'userBlockType'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlockReason(): ActiveQuery
    {
        return $this->hasOne(UserBlockReason::class, ['id' => 'user_block_reason_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserBlockType(): ActiveQuery
    {
        return $this->hasOne(UserBlockType::class, ['id' => 'user_block_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
