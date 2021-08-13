<?php

// TODO refactor

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
            ->andWhere(['season_id' => $seasonId])
            ->groupBy(['federation_id', 'division_id'])
            ->orderBy(['federation_id' => SORT_ASC, 'division_id' => SORT_ASC])
            ->all();
    }
}
