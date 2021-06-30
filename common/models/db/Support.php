<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * Class Support
 * @package common\models\db
 *
 * @property int $id
 * @property int $admin_user_id
 * @property int $date
 * @property bool $is_question
 * @property int $read
 * @property string $text
 * @property int $user_id
 *
 * @property-read User $adminUser
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
            [['admin_user_id'], AtLeastValidator::class, 'in' => ['admin_user_id', 'user_id']],
            [['is_question'], 'boolean'],
            [['admin_user_id', 'read', 'user_id'], 'integer', 'min' => 1],
            [['text'], 'trim'],
            [['text'], 'string'],
            [['admin_user_id'], 'exist', 'targetRelation' => 'adminUser'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function addQuestion(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        $this->user_id = Yii::$app->user->id;
        $this->is_question = true;

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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
