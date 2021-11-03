<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\LeagueDistribution;
use common\models\db\RatingFederation;
use common\models\db\Season;
use Yii;
use yii\db\Exception;

/**
 * Class LeagueLimit
 * @package console\models\newSeason
 */
class LeagueLimit
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason() + 2;

        $data = [];

        $ratingCountryArray = RatingFederation::find()
            ->orderBy(['league_place' => SORT_ASC])
            ->all();
        foreach ($ratingCountryArray as $ratingCountry) {
            if ($ratingCountry->league_place <= 3) {
                $data[] = [$ratingCountry->federation_id, 3, 3, 0, 0, $seasonId];
            } elseif ($ratingCountry->league_place <= 4) {
                $data[] = [$ratingCountry->federation_id, 3, 2, 1, 0, $seasonId];
            } elseif ($ratingCountry->league_place <= 6) {
                $data[] = [$ratingCountry->federation_id, 2, 2, 1, 0, $seasonId];
            } elseif ($ratingCountry->league_place <= 8) {
                $data[] = [$ratingCountry->federation_id, 1, 2, 2, 0, $seasonId];
            } else {
                $data[] = [$ratingCountry->federation_id, 0, 2, 1, 1, $seasonId];
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                LeagueDistribution::tableName(),
                [
                    'federation_id',
                    'group',
                    'qualification_3',
                    'qualification_2',
                    'qualification_1',
                    'season_id',
                ],
                $data
            )
            ->execute();
    }
}
