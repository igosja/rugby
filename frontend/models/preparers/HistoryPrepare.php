<?php

namespace frontend\models\preparers;

use frontend\models\queries\HistoryQuery;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class HistoryPrepare
 * @package frontend\models\preparers
 */
class HistoryPrepare
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
            'query' => HistoryQuery::getTeamHistoryListQuery($teamId, $seasonId),
        ]);
    }
}