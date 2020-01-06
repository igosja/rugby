<?php

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
            ->with([
                'game.nationalGuest',
                'game.nationalHome',
                'game.schedule.stage',
                'game.schedule.tournamentType',
                'game.teamGuest',
                'game.teamHome',
                'position',
            ])
            ->where(['lineup_player_id' => $playerId])
            ->andWhere(['schedule.schedule_season_id' => $seasonId])
            ->andWhere(['!=', 'game.game_played', 0])
            ->orderBy(['schedule_date' => SORT_ASC]);
    }
}
