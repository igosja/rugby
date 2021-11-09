<?php

// TODO refactor

namespace backend\models\queries;

use common\models\db\Payment;
use yii\db\ActiveQuery;
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
    public static function getPaymentForHighCharts()
    {
        return (new Query())
            ->select(['date' => 'FROM_UNIXTIME(`date`, \'%b %Y\')', 'total' => 'SUM(`sum`)'])
            ->from(Payment::tableName())
            ->where(['status' => Payment::PAID])
            ->groupBy(new Expression('FROM_UNIXTIME(`date`, \'%b-%Y\')'))
            ->all();
    }

    /**
     * @return ActiveQuery
     */
    public static function getLastTenPaidPaymentsQuery(): ActiveQuery
    {
        return Payment::find()
            ->with(['user'])
            ->andWhere(['status' => Payment::PAID])
            ->limit(10)
            ->orderBy(['id' => SORT_DESC]);
    }
}
