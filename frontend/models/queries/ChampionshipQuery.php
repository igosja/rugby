<?php

namespace frontend\models\queries;

use common\models\db\Championship;
use yii\db\ActiveQuery;

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
                'country' => function (ActiveQuery $query) {
                    $query->select([
                        'country_id',
                        'country_name',
                    ]);
                },
                'division' => function (ActiveQuery $query) {
                    $query->select([
                        'division_id',
                        'division_name',
                    ]);
                },
            ])
            ->select([
                'championship_country_id',
                'championship_division_id',
                'championship_id',
            ])
            ->where(['championship_season_id' => $seasonId])
            ->groupBy(['championship_country_id', 'championship_division_id'])
            ->orderBy(['championship_country_id' => SORT_ASC, 'championship_division_id' => SORT_ASC])
            ->all();
    }
}
