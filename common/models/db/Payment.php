<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\behaviors\TimestampBehavior;
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

    public const MERCHANT_ID = 27937;
    public const MERCHANT_SECRET = 'h8lzyqfr';
    public const MERCHANT_SECRET_KEY = 's3lyp66r';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%payment}}';
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
     * @return string
     */
    public function paymentUrl(): string
    {
        $merchantId = self::MERCHANT_ID;
        $secretKey = self::MERCHANT_SECRET_KEY;
        $orderId = $this->id;

        $params = [
            'm' => $merchantId,
            'oa' => $this->sum * 50,
            'o' => $orderId,
            's' => md5($merchantId . ':' . $this->sum * 50 . ':' . $secretKey . ':' . $orderId),
            'lang' => 'ru',
        ];

        return 'http://www.free-kassa.ru/merchant/cash.php?' . http_build_query($params);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
