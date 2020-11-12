<?php

// TODO refactor

namespace console\models\start;

use common\models\db\Championship;
use common\models\db\City;
use common\models\db\Division;
use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\TournamentType;
use Yii;
use yii\db\Exception;

/**
 * Class InsertChampionship
 * @package console\models\start
 */
class InsertChampionship
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $seasonId = Season::getCurrentSeason();

        $data = [];

        $countryArray = City::find()
            ->joinWith(['stadiums.team'])
            ->where(['!=', 'city_id', 0])
            ->groupBy('city_country_id')
            ->having('COUNT(team_id)>=16')
            ->orderBy(['city_country_id' => SORT_ASC])
            ->each();

        foreach ($countryArray as $country) {
            /**
             * @var City $country
             */
            $teamArray = Team::find()
                ->joinWith(['stadium.city'])
                ->where(['city_country_id' => $country->city_country_id])
                ->orderBy(['team_id' => SORT_ASC])
                ->limit(16)
                ->each();

            foreach ($teamArray as $team) {
                /**
                 * @var Team $team
                 */
                $data[] = [$country->city_country_id, Division::D1, $seasonId, $team->team_id];
            }

            $teamArray = Team::find()
                ->joinWith(['stadium.city'])
                ->where(['city_country_id' => $country->city_country_id])
                ->orderBy(['team_id' => SORT_ASC])
                ->offset(16)
                ->limit(16)
                ->each();
            foreach ($teamArray as $team) {
                /**
                 * @var Team $team
                 */
                $data[] = [$country->city_country_id, Division::D2, $seasonId, $team->team_id];
            }
        }

        Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Championship::tableName(),
                [
                    'championship_country_id',
                    'championship_division_id',
                    'championship_season_id',
                    'championship_team_id',
                ],
                $data
            )
            ->execute();

        $scheduleArray = Schedule::find()
            ->where([
                'schedule_season_id' => $seasonId,
                'schedule_tournament_type_id' => TournamentType::CHAMPIONSHIP
            ])
            ->orderBy(['schedule_id' => SORT_ASC])
            ->limit(30)
            ->all();

        $scheduleId01 = $scheduleArray[0]->schedule_id;
        $scheduleId02 = $scheduleArray[1]->schedule_id;
        $scheduleId03 = $scheduleArray[2]->schedule_id;
        $scheduleId04 = $scheduleArray[3]->schedule_id;
        $scheduleId05 = $scheduleArray[4]->schedule_id;
        $scheduleId06 = $scheduleArray[5]->schedule_id;
        $scheduleId07 = $scheduleArray[6]->schedule_id;
        $scheduleId08 = $scheduleArray[7]->schedule_id;
        $scheduleId09 = $scheduleArray[8]->schedule_id;
        $scheduleId10 = $scheduleArray[9]->schedule_id;
        $scheduleId11 = $scheduleArray[10]->schedule_id;
        $scheduleId12 = $scheduleArray[11]->schedule_id;
        $scheduleId13 = $scheduleArray[12]->schedule_id;
        $scheduleId14 = $scheduleArray[13]->schedule_id;
        $scheduleId15 = $scheduleArray[14]->schedule_id;
        $scheduleId16 = $scheduleArray[15]->schedule_id;
        $scheduleId17 = $scheduleArray[16]->schedule_id;
        $scheduleId18 = $scheduleArray[17]->schedule_id;
        $scheduleId19 = $scheduleArray[18]->schedule_id;
        $scheduleId20 = $scheduleArray[19]->schedule_id;
        $scheduleId21 = $scheduleArray[20]->schedule_id;
        $scheduleId22 = $scheduleArray[21]->schedule_id;
        $scheduleId23 = $scheduleArray[22]->schedule_id;
        $scheduleId24 = $scheduleArray[23]->schedule_id;
        $scheduleId25 = $scheduleArray[24]->schedule_id;
        $scheduleId26 = $scheduleArray[25]->schedule_id;
        $scheduleId27 = $scheduleArray[26]->schedule_id;
        $scheduleId28 = $scheduleArray[27]->schedule_id;
        $scheduleId29 = $scheduleArray[28]->schedule_id;
        $scheduleId30 = $scheduleArray[29]->schedule_id;

        foreach ($countryArray as $country) {
            for ($i = Division::D1; $i <= Division::D2; $i++) {
                /**
                 * @var Championship[] $teamArray
                 */
                $teamArray = Championship::find()
                    ->with(['team'])
                    ->where([
                        'championship_country_id' => $country->city_country_id,
                        'championship_division_id' => $i,
                        'championship_season_id' => $seasonId,
                    ])
                    ->orderBy('RAND()')
                    ->all();
                $teamId01 = $teamArray[0]->championship_team_id;
                $teamId02 = $teamArray[1]->championship_team_id;
                $teamId03 = $teamArray[2]->championship_team_id;
                $teamId04 = $teamArray[3]->championship_team_id;
                $teamId05 = $teamArray[4]->championship_team_id;
                $teamId06 = $teamArray[5]->championship_team_id;
                $teamId07 = $teamArray[6]->championship_team_id;
                $teamId08 = $teamArray[7]->championship_team_id;
                $teamId09 = $teamArray[8]->championship_team_id;
                $teamId10 = $teamArray[9]->championship_team_id;
                $teamId11 = $teamArray[10]->championship_team_id;
                $teamId12 = $teamArray[11]->championship_team_id;
                $teamId13 = $teamArray[12]->championship_team_id;
                $teamId14 = $teamArray[13]->championship_team_id;
                $teamId15 = $teamArray[14]->championship_team_id;
                $teamId16 = $teamArray[15]->championship_team_id;

                $stadiumId01 = $teamArray[0]->team->team_stadium_id;
                $stadiumId02 = $teamArray[1]->team->team_stadium_id;
                $stadiumId03 = $teamArray[2]->team->team_stadium_id;
                $stadiumId04 = $teamArray[3]->team->team_stadium_id;
                $stadiumId05 = $teamArray[4]->team->team_stadium_id;
                $stadiumId06 = $teamArray[5]->team->team_stadium_id;
                $stadiumId07 = $teamArray[6]->team->team_stadium_id;
                $stadiumId08 = $teamArray[7]->team->team_stadium_id;
                $stadiumId09 = $teamArray[8]->team->team_stadium_id;
                $stadiumId10 = $teamArray[9]->team->team_stadium_id;
                $stadiumId11 = $teamArray[10]->team->team_stadium_id;
                $stadiumId12 = $teamArray[11]->team->team_stadium_id;
                $stadiumId13 = $teamArray[12]->team->team_stadium_id;
                $stadiumId14 = $teamArray[13]->team->team_stadium_id;
                $stadiumId15 = $teamArray[14]->team->team_stadium_id;
                $stadiumId16 = $teamArray[15]->team->team_stadium_id;

                $data = [
                    [$teamId02, $teamId01, $scheduleId01, $stadiumId02],
                    [$teamId03, $teamId15, $scheduleId01, $stadiumId03],
                    [$teamId04, $teamId14, $scheduleId01, $stadiumId04],
                    [$teamId05, $teamId13, $scheduleId01, $stadiumId05],
                    [$teamId06, $teamId12, $scheduleId01, $stadiumId06],
                    [$teamId07, $teamId11, $scheduleId01, $stadiumId07],
                    [$teamId08, $teamId10, $scheduleId01, $stadiumId08],
                    [$teamId16, $teamId09, $scheduleId01, $stadiumId16],
                    [$teamId01, $teamId03, $scheduleId02, $stadiumId01],
                    [$teamId02, $teamId16, $scheduleId02, $stadiumId02],
                    [$teamId10, $teamId09, $scheduleId02, $stadiumId10],
                    [$teamId11, $teamId08, $scheduleId02, $stadiumId11],
                    [$teamId12, $teamId07, $scheduleId02, $stadiumId12],
                    [$teamId13, $teamId06, $scheduleId02, $stadiumId13],
                    [$teamId14, $teamId05, $scheduleId02, $stadiumId14],
                    [$teamId15, $teamId04, $scheduleId02, $stadiumId15],
                    [$teamId03, $teamId02, $scheduleId03, $stadiumId03],
                    [$teamId04, $teamId01, $scheduleId03, $stadiumId04],
                    [$teamId05, $teamId15, $scheduleId03, $stadiumId05],
                    [$teamId06, $teamId14, $scheduleId03, $stadiumId06],
                    [$teamId07, $teamId13, $scheduleId03, $stadiumId07],
                    [$teamId08, $teamId12, $scheduleId03, $stadiumId08],
                    [$teamId09, $teamId11, $scheduleId03, $stadiumId09],
                    [$teamId16, $teamId10, $scheduleId03, $stadiumId16],
                    [$teamId01, $teamId05, $scheduleId04, $stadiumId01],
                    [$teamId02, $teamId04, $scheduleId04, $stadiumId02],
                    [$teamId03, $teamId16, $scheduleId04, $stadiumId03],
                    [$teamId11, $teamId10, $scheduleId04, $stadiumId11],
                    [$teamId12, $teamId09, $scheduleId04, $stadiumId12],
                    [$teamId13, $teamId08, $scheduleId04, $stadiumId13],
                    [$teamId14, $teamId07, $scheduleId04, $stadiumId14],
                    [$teamId15, $teamId06, $scheduleId04, $stadiumId15],
                    [$teamId04, $teamId03, $scheduleId05, $stadiumId04],
                    [$teamId05, $teamId02, $scheduleId05, $stadiumId05],
                    [$teamId06, $teamId01, $scheduleId05, $stadiumId06],
                    [$teamId07, $teamId15, $scheduleId05, $stadiumId07],
                    [$teamId08, $teamId14, $scheduleId05, $stadiumId08],
                    [$teamId09, $teamId13, $scheduleId05, $stadiumId09],
                    [$teamId10, $teamId12, $scheduleId05, $stadiumId10],
                    [$teamId16, $teamId11, $scheduleId05, $stadiumId16],
                    [$teamId01, $teamId07, $scheduleId06, $stadiumId01],
                    [$teamId02, $teamId06, $scheduleId06, $stadiumId02],
                    [$teamId03, $teamId05, $scheduleId06, $stadiumId03],
                    [$teamId04, $teamId16, $scheduleId06, $stadiumId04],
                    [$teamId12, $teamId11, $scheduleId06, $stadiumId12],
                    [$teamId13, $teamId10, $scheduleId06, $stadiumId13],
                    [$teamId14, $teamId09, $scheduleId06, $stadiumId14],
                    [$teamId15, $teamId08, $scheduleId06, $stadiumId15],
                    [$teamId05, $teamId04, $scheduleId07, $stadiumId05],
                    [$teamId06, $teamId03, $scheduleId07, $stadiumId06],
                    [$teamId07, $teamId02, $scheduleId07, $stadiumId07],
                    [$teamId08, $teamId01, $scheduleId07, $stadiumId08],
                    [$teamId09, $teamId15, $scheduleId07, $stadiumId09],
                    [$teamId10, $teamId14, $scheduleId07, $stadiumId10],
                    [$teamId11, $teamId13, $scheduleId07, $stadiumId11],
                    [$teamId16, $teamId12, $scheduleId07, $stadiumId16],
                    [$teamId01, $teamId09, $scheduleId08, $stadiumId01],
                    [$teamId02, $teamId08, $scheduleId08, $stadiumId02],
                    [$teamId03, $teamId07, $scheduleId08, $stadiumId03],
                    [$teamId04, $teamId06, $scheduleId08, $stadiumId04],
                    [$teamId05, $teamId16, $scheduleId08, $stadiumId05],
                    [$teamId13, $teamId12, $scheduleId08, $stadiumId13],
                    [$teamId14, $teamId11, $scheduleId08, $stadiumId14],
                    [$teamId15, $teamId10, $scheduleId08, $stadiumId15],
                    [$teamId06, $teamId05, $scheduleId09, $stadiumId06],
                    [$teamId07, $teamId04, $scheduleId09, $stadiumId07],
                    [$teamId08, $teamId03, $scheduleId09, $stadiumId08],
                    [$teamId09, $teamId02, $scheduleId09, $stadiumId09],
                    [$teamId10, $teamId01, $scheduleId09, $stadiumId10],
                    [$teamId11, $teamId15, $scheduleId09, $stadiumId11],
                    [$teamId12, $teamId14, $scheduleId09, $stadiumId12],
                    [$teamId16, $teamId13, $scheduleId09, $stadiumId16],
                    [$teamId01, $teamId11, $scheduleId10, $stadiumId01],
                    [$teamId02, $teamId10, $scheduleId10, $stadiumId02],
                    [$teamId03, $teamId09, $scheduleId10, $stadiumId03],
                    [$teamId04, $teamId08, $scheduleId10, $stadiumId04],
                    [$teamId05, $teamId07, $scheduleId10, $stadiumId05],
                    [$teamId06, $teamId16, $scheduleId10, $stadiumId06],
                    [$teamId14, $teamId13, $scheduleId10, $stadiumId14],
                    [$teamId15, $teamId12, $scheduleId10, $stadiumId15],
                    [$teamId07, $teamId06, $scheduleId11, $stadiumId07],
                    [$teamId08, $teamId05, $scheduleId11, $stadiumId08],
                    [$teamId09, $teamId04, $scheduleId11, $stadiumId09],
                    [$teamId10, $teamId03, $scheduleId11, $stadiumId10],
                    [$teamId11, $teamId02, $scheduleId11, $stadiumId11],
                    [$teamId12, $teamId01, $scheduleId11, $stadiumId12],
                    [$teamId13, $teamId15, $scheduleId11, $stadiumId13],
                    [$teamId16, $teamId14, $scheduleId11, $stadiumId16],
                    [$teamId01, $teamId13, $scheduleId12, $stadiumId01],
                    [$teamId02, $teamId12, $scheduleId12, $stadiumId02],
                    [$teamId03, $teamId11, $scheduleId12, $stadiumId03],
                    [$teamId04, $teamId10, $scheduleId12, $stadiumId04],
                    [$teamId05, $teamId09, $scheduleId12, $stadiumId05],
                    [$teamId06, $teamId08, $scheduleId12, $stadiumId06],
                    [$teamId07, $teamId16, $scheduleId12, $stadiumId07],
                    [$teamId15, $teamId14, $scheduleId12, $stadiumId15],
                    [$teamId08, $teamId07, $scheduleId13, $stadiumId08],
                    [$teamId09, $teamId06, $scheduleId13, $stadiumId09],
                    [$teamId10, $teamId05, $scheduleId13, $stadiumId10],
                    [$teamId11, $teamId04, $scheduleId13, $stadiumId11],
                    [$teamId12, $teamId03, $scheduleId13, $stadiumId12],
                    [$teamId13, $teamId02, $scheduleId13, $stadiumId13],
                    [$teamId14, $teamId01, $scheduleId13, $stadiumId14],
                    [$teamId16, $teamId15, $scheduleId13, $stadiumId16],
                    [$teamId01, $teamId15, $scheduleId14, $stadiumId01],
                    [$teamId02, $teamId14, $scheduleId14, $stadiumId02],
                    [$teamId03, $teamId13, $scheduleId14, $stadiumId03],
                    [$teamId04, $teamId12, $scheduleId14, $stadiumId04],
                    [$teamId05, $teamId11, $scheduleId14, $stadiumId05],
                    [$teamId06, $teamId10, $scheduleId14, $stadiumId06],
                    [$teamId07, $teamId09, $scheduleId14, $stadiumId07],
                    [$teamId16, $teamId08, $scheduleId14, $stadiumId16],
                    [$teamId09, $teamId08, $scheduleId15, $stadiumId09],
                    [$teamId10, $teamId07, $scheduleId15, $stadiumId10],
                    [$teamId11, $teamId06, $scheduleId15, $stadiumId11],
                    [$teamId12, $teamId05, $scheduleId15, $stadiumId12],
                    [$teamId13, $teamId04, $scheduleId15, $stadiumId13],
                    [$teamId14, $teamId03, $scheduleId15, $stadiumId14],
                    [$teamId15, $teamId02, $scheduleId15, $stadiumId15],
                    [$teamId16, $teamId01, $scheduleId15, $stadiumId16],
                    [$teamId01, $teamId02, $scheduleId16, $stadiumId01],
                    [$teamId15, $teamId03, $scheduleId16, $stadiumId15],
                    [$teamId14, $teamId04, $scheduleId16, $stadiumId14],
                    [$teamId13, $teamId05, $scheduleId16, $stadiumId13],
                    [$teamId12, $teamId06, $scheduleId16, $stadiumId12],
                    [$teamId11, $teamId07, $scheduleId16, $stadiumId11],
                    [$teamId10, $teamId08, $scheduleId16, $stadiumId10],
                    [$teamId09, $teamId16, $scheduleId16, $stadiumId09],
                    [$teamId03, $teamId01, $scheduleId17, $stadiumId03],
                    [$teamId16, $teamId02, $scheduleId17, $stadiumId16],
                    [$teamId09, $teamId10, $scheduleId17, $stadiumId09],
                    [$teamId08, $teamId11, $scheduleId17, $stadiumId08],
                    [$teamId07, $teamId12, $scheduleId17, $stadiumId07],
                    [$teamId06, $teamId13, $scheduleId17, $stadiumId06],
                    [$teamId05, $teamId14, $scheduleId17, $stadiumId05],
                    [$teamId04, $teamId15, $scheduleId17, $stadiumId04],
                    [$teamId02, $teamId03, $scheduleId18, $stadiumId02],
                    [$teamId01, $teamId04, $scheduleId18, $stadiumId01],
                    [$teamId15, $teamId05, $scheduleId18, $stadiumId15],
                    [$teamId14, $teamId06, $scheduleId18, $stadiumId14],
                    [$teamId13, $teamId07, $scheduleId18, $stadiumId13],
                    [$teamId12, $teamId08, $scheduleId18, $stadiumId12],
                    [$teamId11, $teamId09, $scheduleId18, $stadiumId11],
                    [$teamId10, $teamId16, $scheduleId18, $stadiumId10],
                    [$teamId05, $teamId01, $scheduleId19, $stadiumId05],
                    [$teamId04, $teamId02, $scheduleId19, $stadiumId04],
                    [$teamId16, $teamId03, $scheduleId19, $stadiumId16],
                    [$teamId10, $teamId11, $scheduleId19, $stadiumId10],
                    [$teamId09, $teamId12, $scheduleId19, $stadiumId09],
                    [$teamId08, $teamId13, $scheduleId19, $stadiumId08],
                    [$teamId07, $teamId14, $scheduleId19, $stadiumId07],
                    [$teamId06, $teamId15, $scheduleId19, $stadiumId06],
                    [$teamId03, $teamId04, $scheduleId20, $stadiumId03],
                    [$teamId02, $teamId05, $scheduleId20, $stadiumId02],
                    [$teamId01, $teamId06, $scheduleId20, $stadiumId01],
                    [$teamId15, $teamId07, $scheduleId20, $stadiumId15],
                    [$teamId14, $teamId08, $scheduleId20, $stadiumId14],
                    [$teamId13, $teamId09, $scheduleId20, $stadiumId13],
                    [$teamId12, $teamId10, $scheduleId20, $stadiumId12],
                    [$teamId11, $teamId16, $scheduleId20, $stadiumId11],
                    [$teamId07, $teamId01, $scheduleId21, $stadiumId07],
                    [$teamId06, $teamId02, $scheduleId21, $stadiumId06],
                    [$teamId05, $teamId03, $scheduleId21, $stadiumId05],
                    [$teamId16, $teamId04, $scheduleId21, $stadiumId16],
                    [$teamId11, $teamId12, $scheduleId21, $stadiumId11],
                    [$teamId10, $teamId13, $scheduleId21, $stadiumId10],
                    [$teamId09, $teamId14, $scheduleId21, $stadiumId09],
                    [$teamId08, $teamId15, $scheduleId21, $stadiumId08],
                    [$teamId04, $teamId05, $scheduleId22, $stadiumId04],
                    [$teamId03, $teamId06, $scheduleId22, $stadiumId03],
                    [$teamId02, $teamId07, $scheduleId22, $stadiumId02],
                    [$teamId01, $teamId08, $scheduleId22, $stadiumId01],
                    [$teamId15, $teamId09, $scheduleId22, $stadiumId15],
                    [$teamId14, $teamId10, $scheduleId22, $stadiumId14],
                    [$teamId13, $teamId11, $scheduleId22, $stadiumId13],
                    [$teamId12, $teamId16, $scheduleId22, $stadiumId12],
                    [$teamId09, $teamId01, $scheduleId23, $stadiumId09],
                    [$teamId08, $teamId02, $scheduleId23, $stadiumId08],
                    [$teamId07, $teamId03, $scheduleId23, $stadiumId07],
                    [$teamId06, $teamId04, $scheduleId23, $stadiumId06],
                    [$teamId16, $teamId05, $scheduleId23, $stadiumId16],
                    [$teamId12, $teamId13, $scheduleId23, $stadiumId12],
                    [$teamId11, $teamId14, $scheduleId23, $stadiumId11],
                    [$teamId10, $teamId15, $scheduleId23, $stadiumId10],
                    [$teamId05, $teamId06, $scheduleId24, $stadiumId05],
                    [$teamId04, $teamId07, $scheduleId24, $stadiumId04],
                    [$teamId03, $teamId08, $scheduleId24, $stadiumId03],
                    [$teamId02, $teamId09, $scheduleId24, $stadiumId02],
                    [$teamId01, $teamId10, $scheduleId24, $stadiumId01],
                    [$teamId15, $teamId11, $scheduleId24, $stadiumId15],
                    [$teamId14, $teamId12, $scheduleId24, $stadiumId14],
                    [$teamId13, $teamId16, $scheduleId24, $stadiumId13],
                    [$teamId11, $teamId01, $scheduleId25, $stadiumId11],
                    [$teamId10, $teamId02, $scheduleId25, $stadiumId10],
                    [$teamId09, $teamId03, $scheduleId25, $stadiumId09],
                    [$teamId08, $teamId04, $scheduleId25, $stadiumId08],
                    [$teamId07, $teamId05, $scheduleId25, $stadiumId07],
                    [$teamId16, $teamId06, $scheduleId25, $stadiumId16],
                    [$teamId13, $teamId14, $scheduleId25, $stadiumId13],
                    [$teamId12, $teamId15, $scheduleId25, $stadiumId12],
                    [$teamId06, $teamId07, $scheduleId26, $stadiumId06],
                    [$teamId05, $teamId08, $scheduleId26, $stadiumId05],
                    [$teamId04, $teamId09, $scheduleId26, $stadiumId04],
                    [$teamId03, $teamId10, $scheduleId26, $stadiumId03],
                    [$teamId02, $teamId11, $scheduleId26, $stadiumId02],
                    [$teamId01, $teamId12, $scheduleId26, $stadiumId01],
                    [$teamId15, $teamId13, $scheduleId26, $stadiumId15],
                    [$teamId14, $teamId16, $scheduleId26, $stadiumId14],
                    [$teamId13, $teamId01, $scheduleId27, $stadiumId13],
                    [$teamId12, $teamId02, $scheduleId27, $stadiumId12],
                    [$teamId11, $teamId03, $scheduleId27, $stadiumId11],
                    [$teamId10, $teamId04, $scheduleId27, $stadiumId10],
                    [$teamId09, $teamId05, $scheduleId27, $stadiumId09],
                    [$teamId08, $teamId06, $scheduleId27, $stadiumId08],
                    [$teamId16, $teamId07, $scheduleId27, $stadiumId16],
                    [$teamId14, $teamId15, $scheduleId27, $stadiumId14],
                    [$teamId07, $teamId08, $scheduleId28, $stadiumId07],
                    [$teamId06, $teamId09, $scheduleId28, $stadiumId06],
                    [$teamId05, $teamId10, $scheduleId28, $stadiumId05],
                    [$teamId04, $teamId11, $scheduleId28, $stadiumId04],
                    [$teamId03, $teamId12, $scheduleId28, $stadiumId03],
                    [$teamId02, $teamId13, $scheduleId28, $stadiumId02],
                    [$teamId01, $teamId14, $scheduleId28, $stadiumId01],
                    [$teamId15, $teamId16, $scheduleId28, $stadiumId15],
                    [$teamId15, $teamId01, $scheduleId29, $stadiumId15],
                    [$teamId14, $teamId02, $scheduleId29, $stadiumId14],
                    [$teamId13, $teamId03, $scheduleId29, $stadiumId13],
                    [$teamId12, $teamId04, $scheduleId29, $stadiumId12],
                    [$teamId11, $teamId05, $scheduleId29, $stadiumId11],
                    [$teamId10, $teamId06, $scheduleId29, $stadiumId10],
                    [$teamId09, $teamId07, $scheduleId29, $stadiumId09],
                    [$teamId08, $teamId16, $scheduleId29, $stadiumId08],
                    [$teamId08, $teamId09, $scheduleId30, $stadiumId08],
                    [$teamId07, $teamId10, $scheduleId30, $stadiumId07],
                    [$teamId06, $teamId11, $scheduleId30, $stadiumId06],
                    [$teamId05, $teamId12, $scheduleId30, $stadiumId05],
                    [$teamId04, $teamId13, $scheduleId30, $stadiumId04],
                    [$teamId03, $teamId14, $scheduleId30, $stadiumId03],
                    [$teamId02, $teamId15, $scheduleId30, $stadiumId02],
                    [$teamId01, $teamId16, $scheduleId30, $stadiumId01],
                ];

                Yii::$app->db
                    ->createCommand()
                    ->batchInsert(
                        Game::tableName(),
                        ['game_home_team_id', 'game_guest_team_id', 'game_schedule_id', 'game_stadium_id'],
                        $data
                    )
                    ->execute();
            }
        }
    }
}
