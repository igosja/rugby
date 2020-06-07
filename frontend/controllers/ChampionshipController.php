<?php

namespace frontend\controllers;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\Country;
use common\models\db\Division;
use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\StatisticChapter;
use common\models\db\StatisticPlayer;
use common\models\db\StatisticTeam;
use common\models\db\StatisticType;
use common\models\db\TournamentType;
use frontend\components\AbstractController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ChampionshipController
 * @package frontend\controllers
 */
class ChampionshipController extends AbstractController
{
    /**
     * @return Response
     */
    public function actionIndex(): Response
    {
        return $this->redirect([
            'championship/table',
            'countryId' => Yii::$app->request->get('countryId', Country::DEFAULT_ID),
            'divisionId' => Yii::$app->request->get('divisionId', Division::D1),
            'seasonId' => Yii::$app->request->get('seasonId', $this->season->season_id),
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTable(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);
        $countryId = Yii::$app->request->get('countryId', Country::DEFAULT_ID);
        $divisionId = Yii::$app->request->get('divisionId', Division::D1);
        $stageId = Yii::$app->request->get('stageId');

        $country = Country::find()
            ->where(['country_id' => $countryId])
            ->limit(1)
            ->one();
        $this->notFound($country);

        if (!$stageId) {
            $schedule = Schedule::find()
                ->where([
                    'schedule_tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'schedule_season_id' => $seasonId,
                ])
                ->andWhere(['<=', 'schedule_date', time()])
                ->andWhere(['<=', 'schedule_stage_id', Stage::TOUR_30])
                ->orderBy(['schedule_date' => SORT_DESC])
                ->limit(1)
                ->one();
            if (!$schedule) {
                $schedule = Schedule::find()
                    ->where([
                        'schedule_tournament_type_id' => TournamentType::CHAMPIONSHIP,
                        'schedule_season_id' => $seasonId,
                    ])
                    ->andWhere(['>', 'schedule_date', time()])
                    ->andWhere(['<=', 'schedule_stage_id', Stage::TOUR_30])
                    ->orderBy(['schedule_date' => SORT_ASC])
                    ->limit(1)
                    ->one();
            }
            $stageId = $schedule->schedule_stage_id;
        } else {
            $schedule = Schedule::find()
                ->where([
                    'schedule_tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'schedule_season_id' => $seasonId,
                    'schedule_stage_id' => $stageId,
                ])
                ->limit(1)
                ->one();
        }

        $this->notFound($schedule);

        $query = Championship::find()
            ->where([
                'championship_season_id' => $seasonId,
                'championship_country_id' => $countryId,
                'championship_division_id' => $divisionId,
            ])
            ->orderBy(['championship_place' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => false,
        ]);

        $stageArray = Schedule::find()
            ->where([
                'schedule_tournament_type_id' => TournamentType::CHAMPIONSHIP,
                'schedule_season_id' => $seasonId,
            ])
            ->andWhere(['<=', 'schedule_stage_id', Stage::TOUR_30])
            ->orderBy(['schedule_stage_id' => SORT_ASC])
            ->all();
        $stageArray = ArrayHelper::map($stageArray, 'stage.stage_id', 'stage.stage_name');

        $gameArray = Game::find()
            ->joinWith(['schedule'])
            ->where([
                'schedule_stage_id' => $stageId,
                'schedule.schedule_season_id' => $seasonId,
                'schedule.schedule_tournament_type_id' => TournamentType::CHAMPIONSHIP,
                'game_home_team_id' => Championship::find()
                    ->select(['championship_team_id'])
                    ->where([
                        'championship_season_id' => $seasonId,
                        'championship_country_id' => $countryId,
                        'championship_division_id' => $divisionId,
                    ])
            ])
            ->orderBy(['game_id' => SORT_ASC])
            ->all();

        $this->seoTitle($country->country_name . '. Национальный чемпионат');

        return $this->render('table', [
            'country' => $country,
            'dataProvider' => $dataProvider,
            'divisionArray' => $this->getDivisionLinksArray($countryId, $seasonId),
            'divisionId' => $divisionId,
            'gameArray' => $gameArray,
            'scheduleId' => $schedule->schedule_id,
            'seasonArray' => $this->getSeasonArray($countryId, $divisionId),
            'seasonId' => $seasonId,
            'stageArray' => $stageArray,
            'stageId' => $stageId,
        ]);
    }

    /**
     * @param $countryId
     * @param $seasonId
     * @return array
     */
    private function getDivisionLinksArray($countryId, $seasonId)
    {
        $result = [];

        $championshipArray = Championship::find()
            ->with(['division'])
            ->where([
                'championship_country_id' => $countryId,
                'championship_season_id' => $seasonId,
            ])
            ->groupBy(['championship_division_id'])
            ->orderBy(['championship_division_id' => SORT_ASC])
            ->all();
        foreach ($championshipArray as $championship) {
            $result[] = [
                'text' => $championship->division->division_name,
                'url' => [
                    'championship/index',
                    'countryId' => $countryId,
                    'divisionId' => $championship->championship_division_id,
                    'seasonId' => $seasonId,
                ]
            ];
        }

        $conference = Conference::find()
            ->joinWith(['team.stadium.city'])
            ->where([
                'city_country_id' => $countryId,
                'conference_season_id' => $seasonId,
            ])
            ->count();
        if ($conference) {
            $result[] = [
                'text' => 'КЛК',
                'url' => [
                    'conference/table',
                    'countryId' => $countryId,
                    'seasonId' => $seasonId,
                ]
            ];
        }
        return $result;
    }

    /**
     * @param int $countryId
     * @param int $divisionId
     * @return array
     */
    private function getSeasonArray($countryId, $divisionId)
    {
        $season = Championship::find()
            ->select(['championship_season_id'])
            ->where(['championship_country_id' => $countryId, 'championship_division_id' => $divisionId])
            ->groupBy(['championship_season_id'])
            ->orderBy(['championship_season_id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($season, 'championship_season_id', 'championship_season_id');
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionStatistics($id = StatisticType::TEAM_NO_PASS)
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);
        $countryId = Yii::$app->request->get('countryId', Country::DEFAULT_ID);
        $divisionId = Yii::$app->request->get('divisionId', Division::D1);

        $country = Country::find()
            ->where(['country_id' => $countryId])
            ->limit(1)
            ->one();
        $this->notFound($country);

        $statisticType = StatisticType::find()
            ->where(['statistic_type_id' => $id])
            ->limit(1)
            ->one();
        if (!$statisticType) {
            $statisticType = StatisticType::find()
                ->where(['statistic_type_id' => StatisticType::TEAM_NO_PASS])
                ->limit(1)
                ->one();
        }

        if ($statisticType->isTeamChapter()) {
            $query = StatisticTeam::find()
                ->where([
                    'statistic_team_country_id' => $countryId,
                    'statistic_team_division_id' => $divisionId,
                    'statistic_team_tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'statistic_team_season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->statistic_type_select => $statisticType->statistic_type_order]);
        } else {
            $query = StatisticPlayer::find()
                ->where([
                    'statistic_player_country_id' => $countryId,
                    'statistic_player_division_id' => $divisionId,
                    'statistic_player_tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'statistic_player_season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->statistic_type_select => $statisticType->statistic_type_order]);
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => false,
        ]);
        $this->seoTitle($country->country_name . '. Статистика национального чемпионата');

        return $this->render('statistics', [
            'country' => $country,
            'dataProvider' => $dataProvider,
            'divisionArray' => $this->getDivisionStatisticsLinksArray($countryId, $seasonId),
            'divisionId' => $divisionId,
            'myTeam' => $this->myTeam,
            'seasonId' => $seasonId,
            'statisticType' => $statisticType,
            'statisticTypeArray' => StatisticChapter::selectOptions(),
        ]);
    }

    /**
     * @param int $countryId
     * @param int $seasonId
     * @return array
     */
    private function getDivisionStatisticsLinksArray(int $countryId, int $seasonId): array
    {
        $result = [];

        $championshipArray = Championship::find()
            ->with(['division'])
            ->where([
                'championship_country_id' => $countryId,
                'championship_season_id' => $seasonId,
            ])
            ->groupBy(['championship_division_id'])
            ->orderBy(['championship_division_id' => SORT_ASC])
            ->all();
        foreach ($championshipArray as $championship) {
            $result[] = [
                'text' => $championship->division->division_name,
                'url' => [
                    'championship/statistics',
                    'countryId' => $countryId,
                    'divisionId' => $championship->division->division_id,
                    'seasonId' => $seasonId,
                ]
            ];
        }
        return $result;
    }
}
