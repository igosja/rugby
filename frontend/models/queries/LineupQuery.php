<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Lineup;
use yii\db\ActiveQuery;

/**
 * Class LineupQuery
 * @package frontend\models\queries
 */
class LineupQuery
{
    /**
     * @param int $playerId
     * @param int $seasonId
     * @return ActiveQuery
     */
    public static function getPlayerLineupListQuery(int $playerId, int $seasonId): ActiveQuery
    {
        return Lineup::find()
            ->joinWith(['game.schedule'])
            ->where(['player_id' => $playerId])
            ->andWhere(['season_id' => $seasonId])
            ->andWhere(['not', ['played' => null]])
            ->orderBy(['date' => SORT_ASC]);
    }
}
