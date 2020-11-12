<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Support
 * @package common\models\db
 *
 * @property int $id
 * @property int $admin_user_id
 * @property int $date
 * @property int $federation_id
 * @property bool $is_inside
 * @property bool $is_question
 * @property int $president_user_id
 * @property int $read
 * @property string $text
 * @property int $user_id
 *
 * @property-read User $adminUser
 * @property-read Federation $federation
 * @property-read User $presidentUser
 * @property-read User $user
 */
class Support extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%support}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['is_question', 'text'], 'required'],
            [['admin_user_id'], AtLeastValidator::class, 'in' => ['admin_user_id', 'president_user_id', 'user_id']],
            [['is_inside', 'is_question'], 'boolean'],
            [['federation_id'], 'min' => 1, 'max' => 999],
            [['admin_user_id', 'president_user_id', 'read', 'user_id'], 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['admin_user_id'], 'exist', 'targetRelation' => 'adminUser'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['president_user_id'], 'exist', 'targetRelation' => 'presidentUser'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAdminUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'admin_user_id']);
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
    public function getPresidentUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'president_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
