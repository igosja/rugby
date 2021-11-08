<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\ErrorHelper;
use Exception;
use Yii;
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
     * @return bool
     */
    public function pay(): bool
    {
        if (!$this->load(Yii::$app->request->post())) {
            return false;
        }

        if (!$this->validate(['sum', 'user_id'])) {
            return false;
        }

        $bonus = $this->paymentBonus($this->user_id);

        if ($this->sum >= 100) {
            $bonus += 10;
        }

        $sum = round($this->sum * (100 + $bonus) / 100, 2);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->log = 'admin payment';
            $this->status = self::PAID;
            $this->save();

            Money::log([
                'money_text_id' => MoneyText::INCOME_ADD_FUNDS,
                'user_id' => $this->user_id,
                'value' => $sum,
                'value_after' => $this->user->money + $sum,
                'value_before' => $this->user->money,
            ]);

            $this->user->money += $sum;
            $this->user->save(true, ['money']);

            if ($this->user->referrerUser) {
                $sum = round($sum / 10, 2);

                Money::log([
                    'money_text_id' => MoneyText::INCOME_REFERRAL,
                    'user_id' => $this->user->referrer_user_id,
                    'value' => $sum,
                    'value_after' => $this->user->referrerUser->money + $sum,
                    'value_before' => $this->user->referrerUser->money,
                ]);

                $this->user->referrerUser->money += $sum;
                $this->user->referrerUser->save(true, ['money']);
            }

            if ($transaction) {
                $transaction->commit();
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     * @param int $userId
     * @return int
     */
    private function paymentBonus(int $userId): int
    {
        $paymentSum = self::find()
            ->where(['user_id' => $userId, 'status' => self::PAID])
            ->sum('sum');

        $result = 0;
        $bonusArray = $this->getBonusArray();
        foreach ($bonusArray as $sum => $bonus) {
            if ($paymentSum > $sum) {
                $result = $bonus;
            }
            if ($paymentSum <= $sum) {
                return $result;
            }
        }

        return end($bonusArray);
    }

    /**
     * @return array
     */
    private function getBonusArray(): array
    {
        return [
            0 => 0,
            10 => 2,
            25 => 4,
            50 => 6,
            75 => 8,
            100 => 10,
            200 => 15,
            300 => 20,
            500 => 25,
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
