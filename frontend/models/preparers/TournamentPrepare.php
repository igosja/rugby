<?php

namespace frontend\models\preparers;

use common\models\db\Division;
use common\models\db\NationalType;
use common\models\db\Schedule;
use common\models\db\TournamentType;
use frontend\models\queries\ChampionshipQuery;
use yii\db\ActiveQuery;
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

        $scheduleArray = Schedule::find()
            ->with([
                'tournamentType' => function (ActiveQuery $query) {
                    $query->select([
                        'tournament_type_id',
                        'tournament_type_name',
                    ]);
                },
            ])
            ->select([
                'schedule_tournament_type_id'
            ])
            ->where(['schedule_season_id' => $seasonId])
            ->groupBy(['schedule_tournament_type_id'])
            ->orderBy(['schedule_tournament_type_id' => SORT_ASC])
            ->all();
        foreach ($scheduleArray as $schedule) {
            if (!in_array($schedule->schedule_tournament_type_id, [
                TournamentType::NATIONAL,
                TournamentType::LEAGUE,
                TournamentType::CONFERENCE,
                TournamentType::OFF_SEASON,
            ])) {
                continue;
            }

            $params = ['seasonId' => $seasonId];
            if (TournamentType::NATIONAL == $schedule->schedule_tournament_type_id) {
                $route = ['world-cup/index'];
                $params = ArrayHelper::merge($params, ['divisionId' => Division::D1, 'nationalTypeId' => NationalType::MAIN]);
            } elseif (TournamentType::LEAGUE == $schedule->schedule_tournament_type_id) {
                $route = ['champions-league/index'];
            } elseif (TournamentType::CONFERENCE == $schedule->schedule_tournament_type_id) {
                $route = ['conference/index'];
            } else {
                $route = ['off-season/index'];
            }

            $tournamentArray[] = Html::a(
                $schedule->tournamentType->tournament_type_name,
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
        $countryId = 0;
        $countryName = '';
        $countryArray = [];
        $divisionArray = [];

        $championshipArray = ChampionshipQuery::getChampionshipsForTournament($seasonId);

        foreach ($championshipArray as $championship) {
            if ($countryId != $championship->championship_country_id) {
                if ($countryId) {
                    $countryArray[] = [
                        'countryId' => $countryId,
                        'countryName' => $countryName,
                        'division' => $divisionArray,
                    ];
                }

                $countryId = $championship->championship_country_id;
                $countryName = $championship->country->country_name;
                $divisionArray = [];
            }

            $divisionArray[$championship->championship_division_id] = $championship->division->division_name;
        }

        if ($countryId) {
            $countryArray[] = [
                'countryId' => $countryId,
                'countryName' => $countryName,
                'division' => $divisionArray,
            ];
        }

        $divisionArray = Division::find()
            ->select([
                'division_id',
                'division_name',
            ])
            ->orderBy(['division_id' => SORT_ASC])
            ->all();

        for ($i = 0, $countCountry = count($countryArray); $i < $countCountry; $i++) {
            foreach ($divisionArray as $division) {
                if (!isset($countryArray[$i]['division'][$division->division_id])) {
                    $countryArray[$i]['division'][$division->division_id] = '-';
                }
            }
        }

        return $countryArray;
    }
}