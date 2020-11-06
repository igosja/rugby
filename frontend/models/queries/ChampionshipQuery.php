<?php

namespace frontend\models\queries;

use common\models\db\Championship;

/**
 * Class ChampionshipQuery
 * @package frontend\models\queries
 */
class ChampionshipQuery
{
    /**
     * @param int $seasonId
     * @return Championship[]
     */
    public static function getChampionshipsForTournament(int $seasonId): array
    {
        return Championship::find()
            ->with([
                'country',
                'division',
            ])
            ->where(['championship_season_id' => $seasonId])
            ->groupBy(['championship_country_id', 'championship_division_id'])
            ->orderBy(['championship_country_id' => SORT_ASC, 'championship_division_id' => SORT_ASC])
            ->all();
    }
}
