<?php

namespace frontend\models\preparers;

use frontend\models\queries\LineupQuery;
use yii\data\ActiveDataProvider;

/**
 * Class LineupPrepare
 * @package frontend\models\preparers
 */
class LineupPrepare
{
    /**
     * @param int $playerId
     * @param int $seasonId
     * @return ActiveDataProvider
     */
    public static function getPlayerDataProvider(int $playerId, int $seasonId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => LineupQuery::getPlayerLineupListQuery($playerId, $seasonId),
        ]);
    }
}