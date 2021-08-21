<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * Class TranslateVote
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $translate_option_id
 * @property int $user_id
 *
 * @property-read TranslateOption $translateOption
 * @property-read User $user
 */
class TranslateVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%translate_vote}}';
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['translate_option_id', 'user_id'], 'required'],
            [['translate_option_id', 'user_id'], 'integer'],
            [['translate_option_id'], 'exist', 'targetRelation' => 'translateOption'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslateOption(): ActiveQuery
    {
        return $this->hasOne(TranslateOption::class, ['id' => 'translate_option_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
