<?php

// TODO refactor

namespace frontend\models\preparers;

use common\models\db\Division;
use common\models\db\NationalType;
use common\models\db\Schedule;
use common\models\db\TournamentType;
use frontend\models\queries\ChampionshipQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class TournamentPrepare
 * @package frontend\models\preparers
 */
class TournamentPrepare
{
    /**
     * @param int $seasonId
     * @return string
     */
    public static function getTournaments(int $seasonId): string
    {
        $tournamentArray = [];

        /**
         * @var Schedule[] $scheduleArray
         */
        $scheduleArray = Schedule::find()
            ->andWhere(['season_id' => $seasonId])
            ->groupBy(['tournament_type_id'])
            ->orderBy(['tournament_type_id' => SORT_ASC])
            ->all();
        foreach ($scheduleArray as $schedule) {
            if (!in_array($schedule->tournament_type_id, [
                TournamentType::NATIONAL,
                TournamentType::LEAGUE,
                TournamentType::CONFERENCE,
                TournamentType::OFF_SEASON,
            ], true)) {
                continue;
            }

            $params = ['seasonId' => $seasonId];
            if (TournamentType::NATIONAL === $schedule->tournament_type_id) {
                $route = ['world-cup/index'];
                $params = ArrayHelper::merge($params, ['divisionId' => Division::D1, 'nationalTypeId' => NationalType::MAIN]);
            } elseif (TournamentType::LEAGUE === $schedule->tournament_type_id) {
                $route = ['league/index'];
            } elseif (TournamentType::CONFERENCE === $schedule->tournament_type_id) {
                $route = ['conference/index'];
            } else {
                $route = ['off-season/index'];
            }

            $tournamentArray[] = Html::a(
                $schedule->tournamentType->name,
                ArrayHelper::merge($route, $params)
            );
        }

        return implode(' | ', $tournamentArray);
    }

    /**
     * @param int $seasonId
     * @return array
     */
    public static function getCountriesWithChampionships(int $seasonId): array
    {
        $countryName = '';
        $countryArray = [];
        $divisionArray = [];
        $federationId = 0;

        $championshipArray = ChampionshipQuery::getChampionshipsForTournament($seasonId);

        foreach ($championshipArray as $championship) {
            if ($federationId !== $championship->federation_id) {
                if ($federationId) {
                    $countryArray[] = [
                        'federationId' => $federationId,
                        'countryName' => $countryName,
                        'division' => $divisionArray,
                    ];
                }

                $federationId = $championship->federation_id;
                $countryName = $championship->federation->country->name;
                $divisionArray = [];
            }

            $divisionArray[$championship->division_id] = $championship->division->name;
        }

        if ($federationId) {
            $countryArray[] = [
                'federationId' => $federationId,
                'countryName' => $countryName,
                'division' => $divisionArray,
            ];
        }

        /**
         * @var Division[] $divisionArray
         */
        $divisionArray = Division::find()
            ->orderBy(['id' => SORT_ASC])
            ->all();

        foreach ($countryArray as $i => $iValue) {
            foreach ($divisionArray as $division) {
                if (!isset($iValue['division'][$division->id])) {
                    $countryArray[$i]['division'][$division->id] = '-';
                }
            }
        }

        return $countryArray;
    }
}