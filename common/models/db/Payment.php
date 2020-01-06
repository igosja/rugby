<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class Payment
 * @package common\models\db
 *
 * @property int $payment_id
 * @property int $payment_date
 * @property string $payment_log
 * @property int $payment_status
 * @property float $payment_sum
 * @property int $payment_user_id
 */
class Payment extends AbstractActiveRecord
{
    const NOT_PAID = 0;
    const PAID = 1;

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
    public function rules(): array
    {
        return [
            [['payment_status'], 'in', 'range' => [self::NOT_PAID, self::PAID]],
            [['payment_id', 'payment_date'], 'integer'],
            [['payment_sum'], 'number', 'min' => 1],
            [['payment_sum'], 'required'],
            [['payment_log'], 'string'],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->payment_date = time();
            if (!$this->payment_user_id) {
                $this->payment_user_id = Yii::$app->user->id;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public static function getPaymentHighChartsData(): array
    {
        $expression = new Expression('FROM_UNIXTIME(`payment_date`, \'%b-%Y\')');
        $payment = (new Query())
            ->select(['date' => 'FROM_UNIXTIME(`payment_date`, \'%b %Y\')', 'total' => 'SUM(`payment_sum`)'])
            ->from(self::tableName())
            ->where(['payment_status' => self::PAID])
            ->groupBy($expression)
            ->all();

        $dateStart = strtotime('-11months', strtotime(date('Y-m-01')));
        $dateEnd = strtotime(date('Y-m-t'));
        $dateArray = self::getDateArrayByMonth($dateStart, $dateEnd);

        $valueArray = [];

        foreach ($dateArray as $date) {
            $inArray = false;

            foreach ($payment as $item) {
                if ($item['date'] == $date) {
                    $valueArray[] = (int)$item['total'];
                    $inArray = true;
                }
            }

            if (false == $inArray) {
                $valueArray[] = 0;
            }
        }

        return [$dateArray, $valueArray];
    }

    /**
     * @param string $dateStart
     * @param string $dateEnd
     * @return array
     */
    public static function getDateArrayByMonth(string $dateStart, string $dateEnd): array
    {
        $dateArray = [];

        while ($dateStart < $dateEnd) {
            $dateArray[] = date('M Y', $dateStart);
            $dateStart = strtotime('+1month', strtotime(date('Y-m-d', $dateStart)));
        }

        return $dateArray;
    }
}
