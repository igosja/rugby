<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
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
     * @return array
     */
    public function rules(): array
    {
        return [
            [['is_question', 'text'], 'required'],
            [['admin_user_id'], AtLeastValidator::class, 'in' => ['admin_user_id', 'president_user_id', 'user_id']],
            [['is_inside', 'is_question'], 'boolean'],
            [['federation_id'], 'integer', 'min' => 1, 'max' => 999],
            [['admin_user_id', 'president_user_id', 'read', 'user_id'], 'integer', 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['admin_user_id'], 'exist', 'targetRelation' => 'adminUser'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['president_user_id'], 'exist', 'targetRelation' => 'presidentUser'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     */
    public function addQuestion(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $this->is_inside = false;
        $this->is_question = true;
        $this->user_id = Yii::$app->user->id;

        if (!$this->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param int $federationId
     * @return bool
     */
    public function addFederationAdminQuestion(int $federationId): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $this->federation_id = $federationId;
        $this->is_inside = false;
        $this->is_question = true;
        $this->president_user_id = Yii::$app->user->id;

        if (!$this->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param int $federationId
     * @return bool
     */
    public function addFederationManagerQuestion(int $federationId): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $this->federation_id = $federationId;
        $this->is_inside = true;
        $this->is_question = true;
        $this->user_id = Yii::$app->user->id;

        if (!$this->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param int $federationId
     * @return bool
     */
    public function addFederationManagerAnswer(int $federationId, int $userId): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $this->federation_id = $federationId;
        $this->is_inside = true;
        $this->is_question = false;
        $this->president_user_id = Yii::$app->user->id;
        $this->user_id = $userId;

        if (!$this->save()) {
            return false;
        }

        return true;
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
