<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Payment
 * @package common\models\db
 *
 * @property int $id
 * @property int $date
 * @property string $log
 * @property int $status
 * @property float $sum
 * @property int $user_id
 *
 * @property-read User $user
 */
class Payment extends AbstractActiveRecord
{
    public const PAID = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%payment}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['status', 'sum', 'user_id'], 'required'],
            [['status'], 'boolean'],
            [['sum'], 'number'],
            [['user_id'], 'integer', 'min' => 1],
            [['log'], 'trim'],
            [['log'], 'string'],
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
