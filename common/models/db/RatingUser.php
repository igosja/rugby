<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class RatingUser
 * @package common\models\db
 *
 * @property int $id
 * @property int $rating_place
 * @property int $user_id
 *
 * @property-read User $user
 */
class RatingUser extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_user}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user_id'], 'required'],
            [['rating_place', 'user_id'], 'integer', 'min' => 0],
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
