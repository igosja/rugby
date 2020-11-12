<?php

// TODO refactor

namespace frontend\models\preparers;

use frontend\models\queries\TransferQuery;
use yii\data\ActiveDataProvider;

/**
 * Class TransferPrepare
 * @package frontend\models\preparers
 */
class TransferPrepare
{
    /**
     * @param int $teamId
     * @return ActiveDataProvider
     */
    public static function getTeamBuyerDataProvider(int $teamId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => TransferQuery::getTeamBuyerTransferList($teamId),
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
            'query' => TransferQuery::getTeamSellerTransferList($teamId),
        ]);
    }
}