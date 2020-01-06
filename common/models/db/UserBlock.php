<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class UserBlock
 * @package common\models\db
 *
 * @property int $user_block_id
 * @property int $user_block_date
 * @property int $user_block_user_block_reason_id
 * @property int $user_block_user_block_type_id
 * @property int $user_block_user_id
 *
 * @property UserBlockReason $userBlockReason
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
     * @return ActiveQuery
     */
    public function getUserBlockReason(): ActiveQuery
    {
        return $this->hasOne(UserBlockReason::class, ['user_block_reason_id' => 'user_block_user_block_reason_id']);
    }
}
