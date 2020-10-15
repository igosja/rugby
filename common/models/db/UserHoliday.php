<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class UserHoliday
 * @package common\models\db
 *
 * @property int $id
 * @property int $date_end
 * @property int $date_start
 * @property int $user_id
 *
 * @property-read User $user
 */
class UserHoliday extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_holiday}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['date_start', 'user_id'], 'required'],
            [['date_end', 'date_start', 'user_id'], 'integer', 'min' => 1],
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
