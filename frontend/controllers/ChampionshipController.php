<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\Country;
use common\models\db\Division;
use common\models\db\Federation;
use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\StatisticChapter;
use common\models\db\StatisticPlayer;
use common\models\db\StatisticTeam;
use common\models\db\StatisticType;
use common\models\db\TournamentType;
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
            'federationId' => Yii::$app->request->get('federationId', Country::DEFAULT_ID),
            'divisionId' => Yii::$app->request->get('divisionId', Division::D1),
            'seasonId' => Yii::$app->request->get('seasonId', $this->season->id),
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTable(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $federationId = Yii::$app->request->get('federationId', Country::DEFAULT_ID);
        $divisionId = Yii::$app->request->get('divisionId', Division::D1);
        $stageId = Yii::$app->request->get('stageId');

        /**
         * @var Federation $federation
         */
        $federation = Federation::find()
            ->where(['id' => $federationId])
            ->limit(1)
            ->one();
        $this->notFound($federation);

        /**
         * @var Schedule $schedule
         */
        if (!$stageId) {
            $schedule = Schedule::find()
                ->where([
                    'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'season_id' => $seasonId,
                ])
                ->andWhere(['<=', 'date', time()])
                ->andWhere(['<=', 'stage_id', Stage::TOUR_30])
                ->orderBy(['date' => SORT_DESC])
                ->limit(1)
                ->one();
            if (!$schedule) {
                $schedule = Schedule::find()
                    ->where([
                        'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                        'season_id' => $seasonId,
                    ])
                    ->andWhere(['>', 'date', time()])
                    ->andWhere(['<=', 'stage_id', Stage::TOUR_30])
                    ->orderBy(['date' => SORT_ASC])
                    ->limit(1)
                    ->one();
            }
            $stageId = $schedule->stage_id;
        } else {
            $schedule = Schedule::find()
                ->where([
                    'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'season_id' => $seasonId,
                    'stage_id' => $stageId,
                ])
                ->limit(1)
                ->one();
        }

        $this->notFound($schedule);

        $query = Championship::find()
            ->where([
                'division_id' => $divisionId,
                'federation_id' => $federationId,
                'season_id' => $seasonId,
            ])
            ->orderBy(['place' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => false,
        ]);

        $stageArray = Schedule::find()
            ->where([
                'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                'season_id' => $seasonId,
            ])
            ->andWhere(['<=', 'stage_id', Stage::TOUR_30])
            ->orderBy(['stage_id' => SORT_ASC])
            ->all();
        $stageArray = ArrayHelper::map($stageArray, 'stage.id', 'stage.name');

        $gameArray = Game::find()
            ->joinWith(['schedule'])
            ->where([
                'stage_id' => $stageId,
                'season_id' => $seasonId,
                'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                'home_team_id' => Championship::find()
                    ->select(['team_id'])
                    ->where([
                        'division_id' => $divisionId,
                        'federation_id' => $federationId,
                        'season_id' => $seasonId,
                    ])
            ])
            ->orderBy(['game.id' => SORT_ASC])
            ->all();

        $this->setSeoTitle($federation->country->name . '. Национальный чемпионат');

        return $this->render('table', [
            'federation' => $federation,
            'dataProvider' => $dataProvider,
            'divisionArray' => $this->getDivisionLinksArray($federationId, $seasonId),
            'divisionId' => $divisionId,
            'gameArray' => $gameArray,
            'seasonArray' => $this->getSeasonArray($federationId, $divisionId),
            'seasonId' => $seasonId,
            'stageArray' => $stageArray,
            'stageId' => $stageId,
        ]);
    }

    /**
     * @param $federationId
     * @param $seasonId
     * @return array
     */
    private function getDivisionLinksArray($federationId, $seasonId): array
    {
        $result = [];

        /**
         * @var Championship[] $championshipArray
         */
        $championshipArray = Championship::find()
            ->with(['division'])
            ->where([
                'federation_id' => $federationId,
                'season_id' => $seasonId,
            ])
            ->groupBy(['division_id'])
            ->orderBy(['division_id' => SORT_ASC])
            ->all();
        foreach ($championshipArray as $championship) {
            $result[] = [
                'text' => $championship->division->name,
                'url' => [
                    'championship/table',
                    'federationId' => $federationId,
                    'divisionId' => $championship->division_id,
                    'seasonId' => $seasonId,
                ]
            ];
        }

        $conference = Conference::find()
            ->joinWith(['team.stadium.city.country.federation'])
            ->where([
                'federation.id' => $federationId,
                'season_id' => $seasonId,
            ])
            ->count();
        if ($conference) {
            $result[] = [
                'text' => 'КЛК',
                'url' => [
                    'conference/table',
                    'federationId' => $federationId,
                    'seasonId' => $seasonId,
                ]
            ];
        }
        return $result;
    }

    /**
     * @param int $federationId
     * @param int $divisionId
     * @return array
     */
    private function getSeasonArray(int $federationId, int $divisionId): array
    {
        $season = Championship::find()
            ->select(['season_id'])
            ->where(['federation_id' => $federationId, 'division_id' => $divisionId])
            ->groupBy(['season_id'])
            ->orderBy(['season_id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($season, 'season_id', 'season_id');
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionStatistics(int $id = StatisticType::TEAM_NO_PASS): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $federationId = Yii::$app->request->get('federationId', Country::DEFAULT_ID);
        $divisionId = Yii::$app->request->get('divisionId', Division::D1);

        $federation = Federation::find()
            ->where(['id' => $federationId])
            ->limit(1)
            ->one();
        $this->notFound($federation);
        
        $statisticType = StatisticType::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if (!$statisticType) {
            $statisticType = StatisticType::find()
                ->where(['id' => StatisticType::TEAM_NO_PASS])
                ->limit(1)
                ->one();
        }

        if (1 === $statisticType->statistic_chapter_id) {
            $query = StatisticTeam::find()
                ->where([
                    'federation_id' => $federationId,
                    'division_id' => $divisionId,
                    'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->select_field => SORT_DESC]);
        } else {
            $query = StatisticPlayer::find()
                ->where([
                    'federation_id' => $federationId,
                    'division_id' => $divisionId,
                    'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->select_field => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider(
            [
                'pagination' => [
                    'pageSize' => Yii::$app->params['pageSizeTable'],
                ],
                'query' => $query,
                'sort' => false,
            ]
        );
        $this->setSeoTitle($federation->country->name . '. Статистика национального чемпионата');

        return $this->render('statistics', [
            'federation' => $federation,
            'dataProvider' => $dataProvider,
            'divisionArray' => $this->getDivisionStatisticsLinksArray($federationId, $seasonId),
            'divisionId' => $divisionId,
            'myTeam' => $this->myTeam,
            'seasonId' => $seasonId,
            'statisticType' => $statisticType,
            'statisticTypeArray' => StatisticChapter::selectOptions(),
        ]);
    }

    /**
     * @param int $federationId
     * @param int $seasonId
     * @return array
     */
    private function getDivisionStatisticsLinksArray(int $federationId, int $seasonId): array
    {
        $result = [];

        /**
         * @var Championship[] $championshipArray
         */
        $championshipArray = Championship::find()
            ->with(['division'])
            ->where([
                'federation_id' => $federationId,
                'season_id' => $seasonId,
            ])
            ->groupBy(['division_id'])
            ->orderBy(['division_id' => SORT_ASC])
            ->all();
        foreach ($championshipArray as $championship) {
            $result[] = [
                'text' => $championship->division->name,
                'url' => [
                    'championship/statistics',
                    'federationId' => $federationId,
                    'divisionId' => $championship->division->id,
                    'seasonId' => $seasonId,
                ]
            ];
        }
        return $result;
    }
}
