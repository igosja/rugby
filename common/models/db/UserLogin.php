<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class UserLogin
 * @package common\models\db
 *
 * @property int $id
 * @property string $agent
 * @property string $ip
 * @property int $user_id
 *
 * @property-read User $user
 */
class UserLogin extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_login}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['agent', 'ip', 'user_id'], 'required'],
            [['agent', 'ip'], 'trim'],
            [['agent', 'ip'], 'string', 'max' => 255],
            [['user_id'], 'integer', 'min' => 1],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
