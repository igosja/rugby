<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Division;
use common\models\db\Game;
use common\models\db\NationalType;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\StatisticChapter;
use common\models\db\StatisticPlayer;
use common\models\db\StatisticTeam;
use common\models\db\StatisticType;
use common\models\db\TournamentType;
use common\models\db\WorldCup;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class WorldCupController
 * @package frontend\controllers
 */
class WorldCupController extends AbstractController
{
    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $divisionId = Yii::$app->request->get('divisionId', Division::D1);
        $nationalTypeId = Yii::$app->request->get('nationalTypeId', NationalType::MAIN);
        $stageId = Yii::$app->request->get('stageId');

        /**
         * @var Schedule $schedule
         */
        if (!$stageId) {
            $schedule = Schedule::find()
                ->where([
                    'tournament_type_id' => TournamentType::NATIONAL,
                    'season_id' => $seasonId,
                ])
                ->andWhere(['<=', 'date', time()])
                ->orderBy(['date' => SORT_DESC])
                ->limit(1)
                ->one();
            if (!$schedule) {
                $schedule = Schedule::find()
                    ->where([
                        'tournament_type_id' => TournamentType::NATIONAL,
                        'season_id' => $seasonId,
                    ])
                    ->andWhere(['>', 'date', time()])
                    ->orderBy(['date' => SORT_ASC])
                    ->limit(1)
                    ->one();
            }

            $this->notFound($schedule);

            $stageId = $schedule->stage_id;
        } else {
            $schedule = Schedule::find()
                ->where([
                    'tournament_type_id' => TournamentType::NATIONAL,
                    'season_id' => $seasonId,
                    'stage_id' => $stageId,
                ])
                ->limit(1)
                ->one();
        }

        $this->notFound($schedule);

        $query = WorldCup::find()
            ->where([
                'season_id' => $seasonId,
                'division_id' => $divisionId,
                'national_type_id' => $nationalTypeId,
            ])
            ->orderBy(['place' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => false,
        ]);

        $stageArray = Schedule::find()
            ->where([
                'tournament_type_id' => TournamentType::NATIONAL,
                'season_id' => $seasonId,
            ])
            ->andWhere(['<=', 'stage_id', Stage::TOUR_30])
            ->orderBy(['stage_id' => SORT_ASC])
            ->all();
        $stageArray = ArrayHelper::map($stageArray, 'stage.id', 'stage.name');

        $gameArray = Game::find()
            ->where([
                'home_national_id' => WorldCup::find()
                    ->select(['national_id'])
                    ->where([
                        'season_id' => $seasonId,
                        'division_id' => $divisionId,
                        'national_type_id' => $nationalTypeId,
                    ])
            ])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select(['id'])
                    ->andWhere([
                        'stage_id' => $stageId,
                        'season_id' => $seasonId,
                        'tournament_type_id' => TournamentType::NATIONAL,
                    ])
            ])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.world-cup.index.title'));

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'divisionArray' => $this->getDivisionLinksArray($seasonId, $nationalTypeId),
            'divisionId' => $divisionId,
            'gameArray' => $gameArray,
            'nationalTypeArray' => $this->getNationalTypeArray($seasonId, $divisionId),
            'nationalTypeId' => $nationalTypeId,
            'seasonArray' => $this->getSeasonArray($divisionId, $nationalTypeId),
            'seasonId' => $seasonId,
            'stageArray' => $stageArray,
            'stageId' => $stageId,
        ]);
    }

    /**
     * @param int $seasonId
     * @param int $nationalTypeId
     * @return array
     */
    private function getDivisionLinksArray(int $seasonId, int $nationalTypeId): array
    {
        $result = [];

        /**
         * @var WorldCup[] $worldCupArray
         */
        $worldCupArray = WorldCup::find()
            ->where([
                'season_id' => $seasonId,
                'national_type_id' => $nationalTypeId,
            ])
            ->groupBy(['division_id'])
            ->orderBy(['division_id' => SORT_ASC])
            ->all();
        foreach ($worldCupArray as $worldCup) {
            $result[] = [
                'alias' => [
                    [
                        'world-cup/index',
                        'divisionId' => $worldCup->division_id,
                        'nationalTypeId' => $worldCup->national_type_id,
                        'seasonId' => $seasonId,
                    ],
                ],
                'text' => $worldCup->division->name,
                'url' => [
                    'world-cup/index',
                    'divisionId' => $worldCup->division_id,
                    'nationalTypeId' => $worldCup->national_type_id,
                    'seasonId' => $seasonId,
                ]
            ];
        }

        return $result;
    }

    /**
     * @param int $seasonId
     * @param int $divisionId
     * @return array
     */
    private function getNationalTypeArray(int $seasonId, int $divisionId): array
    {
        $result = [];

        /**
         * @var WorldCup[] $worldCupArray
         */
        $worldCupArray = WorldCup::find()
            ->where([
                'season_id' => $seasonId,
                'division_id' => $divisionId,
            ])
            ->groupBy(['national_type_id'])
            ->orderBy(['national_type_id' => SORT_ASC])
            ->all();
        foreach ($worldCupArray as $worldCup) {
            $result[] = [
                'alias' => [
                    [
                        'world-cup/index',
                        'divisionId' => $worldCup->division_id,
                        'nationalTypeId' => $worldCup->national_type_id,
                        'seasonId' => $seasonId,
                    ],
                ],
                'text' => $worldCup->nationalType->name,
                'url' => [
                    'world-cup/index',
                    'divisionId' => $worldCup->division_id,
                    'nationalTypeId' => $worldCup->national_type_id,
                    'seasonId' => $seasonId,
                ]
            ];
        }

        return $result;
    }

    /**
     * @param int $divisionId
     * @param int $nationalTypeId
     * @return array
     */
    private function getSeasonArray(int $divisionId, int $nationalTypeId): array
    {
        $season = WorldCup::find()
            ->select(['season_id'])
            ->where([
                'division_id' => $divisionId,
                'national_type_id' => $nationalTypeId,
            ])
            ->groupBy(['season_id'])
            ->orderBy(['season_id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($season, 'season_id', 'season_id');
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionStatistics(int $id = StatisticType::TEAM_NO_PASS): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $divisionId = Yii::$app->request->get('divisionId', Division::D1);

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
                    'division_id' => $divisionId,
                    'tournament_type_id' => TournamentType::NATIONAL,
                    'season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->select_field => SORT_DESC]);
        } else {
            $query = StatisticPlayer::find()
                ->where([
                    'division_id' => $divisionId,
                    'tournament_type_id' => TournamentType::CHAMPIONSHIP,
                    'season_id' => $seasonId,
                ])
                ->orderBy([$statisticType->select_field => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
            'sort' => false,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.world-cup.statistics.title'));

        return $this->render('statistics', [
            'dataProvider' => $dataProvider,
            'divisionArray' => $this->getDivisionStatisticsLinksArray($seasonId),
            'divisionId' => $divisionId,
            'myNational' => $this->myNationalOrVice,
            'seasonId' => $seasonId,
            'statisticType' => $statisticType,
            'statisticTypeArray' => StatisticChapter::selectOptions(),
        ]);
    }

    /**
     * @param int $seasonId
     * @return array
     */
    private function getDivisionStatisticsLinksArray(int $seasonId): array
    {
        $result = [];

        /**
         * @var WorldCup[] $worldCupArray
         */
        $worldCupArray = WorldCup::find()
            ->where([
                'season_id' => $seasonId,
            ])
            ->groupBy(['division_id'])
            ->orderBy(['division_id' => SORT_ASC])
            ->all();
        foreach ($worldCupArray as $worldCup) {
            $result[] = [
                'text' => $worldCup->division->name,
                'url' => [
                    'world-cup/statistics',
                    'divisionId' => $worldCup->division->id,
                    'seasonId' => $seasonId,
                ]
            ];
        }
        return $result;
    }
}
