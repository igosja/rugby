<?php

namespace backend\models\queries;

use common\models\db\Payment;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class PaymentQuery
 * @package backend\models\queries
 */
class PaymentQuery
{
    /**
     * @return Payment[]
     */
    public static function getPaymentForHighCharts(): array
    {
        $expression = new Expression('FROM_UNIXTIME(`date`, \'%b-%Y\')');
        return (new Query())
            ->select(['date' => 'FROM_UNIXTIME(`date`, \'%b %Y\')', 'total' => 'SUM(`sum`)'])
            ->from(Payment::tableName())
            ->where(['status' => Payment::PAID])
            ->groupBy($expression)
            ->all();
    }
}
