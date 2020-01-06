<?php

namespace frontend\models\preparers;

use frontend\models\queries\GameQuery;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class GamePrepare
 * @package frontend\models\preparers
 */
class GamePrepare
{
    /**
     * @param int $scheduleId
     * @return ActiveDataProvider
     */
    public static function getGameDataProvider(int $scheduleId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeGame'],
            ],
            'query' => GameQuery::getGameListQuery($scheduleId),
        ]);
    }

    /**
     * @param int $teamId
     * @param int $seasonId
     * @return ActiveDataProvider
     */
    public static function getTeamGameDataProvider(int $teamId, int $seasonId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => GameQuery::getTeamGameListQuery($teamId, $seasonId),
        ]);
    }
}
