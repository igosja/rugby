<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * Class Money
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property int $money_text_id
 * @property int $user_id
 * @property float $value
 * @property float $value_after
 * @property float $value_before
 *
 * @property-read MoneyText $moneyText
 * @property-read User $user
 */
class Money extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%money}}';
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
            [['money_text_id', 'user_id', 'value', 'value_after', 'value_before'], 'required'],
            [['money_text_id'], 'integer', 'min' => 1, 'max' => 99],
            [['value', 'value_after', 'value_before'], 'number'],
            [['user_id'], 'integer', 'min' => 1],
            [['money_text_id'], 'exist', 'targetRelation' => 'moneyText'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function log(array $data): bool
    {
        $money = new self();
        $money->setAttributes($data);
        $money->save();

        return true;
    }

    /**
     * @return ActiveQuery
     */
    public function getMoneyText(): ActiveQuery
    {
        return $this->hasOne(MoneyText::class, ['id' => 'money_text_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
