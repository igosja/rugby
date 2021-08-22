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
    public function execute()
    {
        $seasonId = Season::getCurrentSeason() + 2;

        $data = [];

        $ratingCountryArray = RatingFederation::find()
            ->orderBy(['rating_country_league_place' => SORT_ASC])
            ->all();
        foreach ($ratingCountryArray as $ratingCountry) {
            if ($ratingCountry->rating_country_league_place <= 2) {
                $data[] = [$ratingCountry->rating_country_country_id, 1, 1, 2, 1, $seasonId];
            } elseif ($ratingCountry->rating_country_league_place <= 6) {
                $data[] = [$ratingCountry->rating_country_country_id, 1, 1, 1, 2, $seasonId];
            } elseif ($ratingCountry->rating_country_league_place <= 10) {
                $data[] = [$ratingCountry->rating_country_country_id, 0, 2, 1, 1, $seasonId];
            } elseif ($ratingCountry->rating_country_league_place <= 12) {
                $data[] = [$ratingCountry->rating_country_country_id, 0, 1, 2, 1, $seasonId];
            } else {
                $data[] = [$ratingCountry->rating_country_country_id, 0, 1, 1, 2, $seasonId];
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                LeagueDistribution::tableName(),
                [
                    'league_distribution_country_id',
                    'league_distribution_group',
                    'league_distribution_qualification_3',
                    'league_distribution_qualification_2',
                    'league_distribution_qualification_1',
                    'league_distribution_season_id',
                ],
                $data
            )
            ->execute();
    }
}
