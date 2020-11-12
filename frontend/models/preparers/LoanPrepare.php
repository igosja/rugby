<?php

// TODO refactor

namespace frontend\models\preparers;

use frontend\models\queries\LoanQuery;
use yii\data\ActiveDataProvider;

/**
 * Class LoanPrepare
 * @package frontend\models\preparers
 */
class LoanPrepare
{
    /**
     * @param int $teamId
     * @return ActiveDataProvider
     */
    public static function getTeamBuyerDataProvider(int $teamId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => LoanQuery::getTeamBuyerTransferList($teamId),
        ]);
    }

    /**
     * @param int $teamId
     * @return ActiveDataProvider
     */
    public static function getTeamSellerDataProvider(int $teamId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => LoanQuery::getTeamSellerTransferList($teamId),
        ]);
    }
}