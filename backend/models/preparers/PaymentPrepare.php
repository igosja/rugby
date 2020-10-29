<?php

namespace backend\models\preparers;

use backend\models\queries\PaymentQuery;
use yii\data\ActiveDataProvider;

/**
 * Class PaymentPrepare
 * @package backend\models\preparers
 */
class PaymentPrepare
{
    /**
     * @return array
     */
    public static function getPaymentHighChartsData(): array
    {
        $payments = PaymentQuery::getPaymentForHighCharts();

        $dateStart = strtotime('-11months', strtotime(date('Y-m-01')));
        $dateEnd = strtotime(date('Y-m-t'));
        $dateArray = self::getDateArrayByMonth($dateStart, $dateEnd);

        $valueArray = [];

        foreach ($dateArray as $date) {
            $inArray = false;

            foreach ($payments as $item) {
                if ($item['date'] === $date) {
                    $valueArray[] = (int)$item['total'] * 50;
                    $inArray = true;
                }
            }

            if (false === $inArray) {
                $valueArray[] = 0;
            }
        }

        return [$dateArray, $valueArray];
    }

    /**
     * @param int $dateStart
     * @param int $dateEnd
     * @return array
     */
    public static function getDateArrayByMonth(int $dateStart, int $dateEnd): array
    {
        $dateArray = [];

        while ($dateStart < $dateEnd) {
            $dateArray[] = date('M Y', $dateStart);
            $newStartDate = strtotime('+1month', strtotime(date('Y-m-d', $dateStart)));
            if (!$newStartDate) {
                break;
            }
            $dateStart = $newStartDate;
        }

        return $dateArray;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function getIndexDataProvider(): ActiveDataProvider
    {
        return new ActiveDataProvider(
            [
                'pagination' => false,
                'query' => PaymentQuery::getLastTenPaidPaymentsQuery(),
            ]
        );
    }
}