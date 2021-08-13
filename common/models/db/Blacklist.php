<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Blacklist
 * @package common\models\db
 *
 * @property int $id
 * @property int $blocked_user_id
 * @property int $owner_user_id
 *
 * @property-read User $blockedUser
 * @property-read User $ownerUser
 */
class Blacklist extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%blacklist}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['blocked_user_id', 'owner_user_id'], 'required'],
            [['blocked_user_id', 'owner_user_id'], 'integer', 'min' => 0],
            [['blocked_user_id'], 'exist', 'targetRelation' => 'blockedUser'],
            [['owner_user_id'], 'exist', 'targetRelation' => 'ownerUser'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBlockedUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'blocked_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOwnerUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'owner_user_id']);
    }
}
