<?php

namespace frontend\models\preparers;

use frontend\models\queries\FinanceQuery;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class FinancePrepare
 * @package frontend\models\preparers
 */
class FinancePrepare
{
    /**
     * @param int $teamId
     * @param int $seasonId
     * @return ActiveDataProvider
     */
    public static function getTeamDataProvider(int $teamId, int $seasonId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => FinanceQuery::getTeamFinanceListQuery($teamId, $seasonId),
        ]);
    }
}