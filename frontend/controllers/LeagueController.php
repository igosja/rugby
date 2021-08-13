<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Game;
use common\models\db\League;
use common\models\db\ParticipantLeague;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\StatisticChapter;
use common\models\db\StatisticPlayer;
use common\models\db\StatisticTeam;
use common\models\db\StatisticType;
use common\models\db\Team;
use common\models\db\TournamentType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class LeagueController
 * @package frontend\controllers
 */
class LeagueController extends AbstractController
{
    /**
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionIndex(): Response
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);

        /**
         * @var Schedule $schedule
         */
        $schedule = Schedule::find()
            ->where([
                'tournament_type_id' => TournamentType::LEAGUE,
                'season_id' => $seasonId,
            ])
            ->andWhere(['<=', 'date', time()])
            ->orderBy(['date' => SORT_DESC])
            ->limit(1)
            ->one();
        if (!$schedule) {
            $schedule = Schedule::find()
                ->where([
                    'tournament_type_id' => TournamentType::LEAGUE,
                    'season_id' => $seasonId,
                ])
                ->andWhere(['>', 'date', time()])
                ->orderBy(['date' => SORT_ASC])
                ->limit(1)
                ->one();
        }

        $this->notFound($schedule);

        if ($schedule->stage_id < Stage::TOUR_LEAGUE_1) {
            return $this->redirect(['qualification', 'seasonId' => $seasonId]);
        }

        if ($schedule->stage_id < Stage::ROUND_OF_16) {
            return $this->redirect(['table', 'seasonId' => $seasonId]);
        }

        return $this->redirect(['playoff', 'seasonId' => $seasonId]);
    }

    /**
     * @return string
     */
    public function actionQualification(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);

        $qualificationArray = [];

        /**
         * @var Stage[] $stageArray
         */
        $stageArray = Stage::find()
            ->where(['id' => [Stage::QUALIFY_1, Stage::QUALIFY_2, Stage::QUALIFY_3]])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($stageArray as $stage) {
            $scheduleId = Schedule::find()
                ->select(['id'])
                ->where([
                    'season_id' => $seasonId,
                    'stage_id' => $stage->id,
                    'tournament_type_id' => TournamentType::LEAGUE,
                ])
                ->orderBy(['id' => SORT_ASC])
                ->column();
            if ($scheduleId) {
                /**
                 * @var Game[] $gameArray
                 */
                $gameArray = Game::find()
                    ->where(['schedule_id' => $scheduleId])
                    ->andWhere([
                        'home_team_id' => ParticipantLeague::find()
                            ->select(['team_id'])
                            ->where(['season_id' => $seasonId])
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                if ($gameArray) {
                    $participantArray = [];

                    foreach ($gameArray as $game) {
                        $inArray = false;

                        foreach ($participantArray as $i => $participant) {
                            /**
                             * @var Team[] $participant
                             */
                            if (in_array($game->home_team_id, [$participant['home']->id, $participant['guest']->id], true)) {
                                $inArray = true;

                                if ($game->home_team_id === $participant['home']->id) {
                                    $formatScore = 'home';
                                } else {
                                    $formatScore = 'guest';
                                }

                                $participantArray[$i]['game'][] = Html::a(
                                    $game->formatScore($formatScore),
                                    ['game/view', 'id' => $game->id]
                                );
                            }
                        }

                        if (false === $inArray) {
                            $participantArray[] = [
                                'home' => $game->homeTeam,
                                'guest' => $game->guestTeam,
                                'game' => [
                                    Html::a(
                                        $game->formatScore(),
                                        ['game/view', 'id' => $game->id]
                                    )
                                ],
                            ];
                        }
                    }

                    $qualificationArray[] = [
                        'stage' => $stage,
                        'participant' => $participantArray,
                    ];
                }
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.league.qualification.title'));
        return $this->render('qualification', [
            'qualificationArray' => $qualificationArray,
            'roundArray' => $this->getRoundLinksArray($seasonId),
            'seasonArray' => $this->getSeasonArray(),
            'seasonId' => $seasonId,
        ]);
    }

    /**
     * @param int $seasonId
     * @return array[]
     */
    private function getRoundLinksArray(int $seasonId): array
    {
        return [
            [
                'text' => Yii::t('frontend', 'controllers.league.round.qualification'),
                'url' => [
                    'league/qualification',
                    'seasonId' => $seasonId,
                ]
            ],
            [
                'text' => Yii::t('frontend', 'controllers.league.round.table'),
                'url' => [
                    'league/table',
                    'seasonId' => $seasonId,
                ]
            ],
            [
                'text' => Yii::t('frontend', 'controllers.league.round.playoff'),
                'url' => [
                    'league/playoff',
                    'seasonId' => $seasonId,
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    private function getSeasonArray(): array
    {
        $season = Schedule::find()
            ->select(['season_id'])
            ->where(['tournament_type_id' => TournamentType::LEAGUE])
            ->groupBy(['season_id'])
            ->orderBy(['season_id' => SORT_DESC])
            ->all();
        return ArrayHelper::map($season, 'season_id', 'season_id');
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTable(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $stageId = Yii::$app->request->get('stageId');

        if (!$stageId) {
            /**
             * @var Schedule $schedule
             */
            $schedule = Schedule::find()
                ->where([
                    'tournament_type_id' => TournamentType::LEAGUE,
                    'season_id' => $seasonId,
                ])
                ->andWhere(['<=', 'date', time()])
                ->andWhere(['>=', 'stage_id', Stage::TOUR_LEAGUE_1])
                ->andWhere(['<=', 'stage_id', Stage::TOUR_LEAGUE_6])
                ->orderBy(['date' => SORT_DESC])
                ->limit(1)
                ->one();
            if (!$schedule) {
                $schedule = Schedule::find()
                    ->where([
                        'tournament_type_id' => TournamentType::LEAGUE,
                        'season_id' => $seasonId,
                    ])
                    ->andWhere(['>', 'date', time()])
                    ->andWhere(['>=', 'stage_id', Stage::TOUR_LEAGUE_1])
                    ->andWhere(['<=', 'stage_id', Stage::TOUR_LEAGUE_6])
                    ->orderBy(['date' => SORT_ASC])
                    ->limit(1)
                    ->one();
            }
            $stageId = $schedule->stage_id;
        } else {
            $schedule = Schedule::find()
                ->where([
                    'tournament_type_id' => TournamentType::LEAGUE,
                    'season_id' => $seasonId,
                    'stage_id' => $stageId,
                ])
                ->limit(1)
                ->one();
        }

        $this->notFound($schedule);

        $groupArray = [
            1 => ['name' => 'A'],
            2 => ['name' => 'B'],
            3 => ['name' => 'C'],
            4 => ['name' => 'D'],
            5 => ['name' => 'E'],
            6 => ['name' => 'F'],
            7 => ['name' => 'G'],
            8 => ['name' => 'H'],
        ];

        for ($group = 1; $group <= 8; $group++) {
            $gameArray = Game::find()
                ->joinWith(['schedule'])
                ->where([
                    'home_team_id' => League::find()
                        ->select(['team_id'])
                        ->where([
                            'group' => $group,
                            'season_id' => $seasonId,
                        ])
                ])
                ->andWhere([
                    'schedule_id' => Schedule::find()
                        ->select(['id'])
                        ->andWhere([
                            'stage_id' => $stageId,
                            'season_id' => $seasonId,
                            'tournament_type_id' => TournamentType::LEAGUE,])
                ])
                ->orderBy(['game_id' => SORT_ASC])
                ->all();
            $groupArray[$group]['game'] = $gameArray;

            $query = League::find()
                ->where([
                    'group' => $group,
                    'season_id' => $seasonId,
                ])
                ->orderBy(['place' => SORT_ASC]);

            $dataProvider = new ActiveDataProvider([
                'pagination' => false,
                'query' => $query,
                'sort' => false,
            ]);

            $groupArray[$group]['team'] = $dataProvider;
        }

        $stageArray = Schedule::find()
            ->where([
                'tournament_type_id' => TournamentType::LEAGUE,
                'season_id' => $seasonId,
            ])
            ->andWhere(['between', 'stage_id', Stage::TOUR_LEAGUE_1, Stage::TOUR_LEAGUE_6])
            ->orderBy(['stage_id' => SORT_ASC])
            ->all();
        $stageArray = ArrayHelper::map($stageArray, 'stage.id', 'stage.name');

        $this->setSeoTitle(Yii::t('frontend', 'controllers.league.table.title'));

        return $this->render('table', [
            'groupArray' => $groupArray,
            'roundArray' => $this->getRoundLinksArray($seasonId),
            'seasonArray' => $this->getSeasonArray(),
            'seasonId' => $seasonId,
            'stageArray' => $stageArray,
            'stageId' => $stageId,
        ]);
    }

    /**
     * @return string
     */
    public function actionPlayoff(): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);

        $playoffArray = [];

        /**
         * @var Stage[] $stageArray
         */
        $stageArray = Stage::find()
            ->where(['id' => [Stage::ROUND_OF_16, Stage::QUARTER, Stage::SEMI, Stage::FINAL_GAME]])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($stageArray as $stage) {
            $scheduleId = Schedule::find()
                ->select(['id'])
                ->where([
                    'season_id' => $seasonId,
                    'stage_id' => $stage->id,
                    'tournament_type_id' => TournamentType::LEAGUE,
                ])
                ->orderBy(['id' => SORT_ASC])
                ->column();
            if ($scheduleId) {
                /**
                 * @var Game[] $gameArray
                 */
                $gameArray = Game::find()
                    ->where(['schedule_id' => $scheduleId])
                    ->andWhere([
                        'home_team_id' => ParticipantLeague::find()
                            ->select(['team_id'])
                            ->where(['season_id' => $seasonId])
                    ])
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                if ($gameArray) {
                    $participantArray = [];

                    foreach ($gameArray as $game) {
                        $inArray = false;

                        foreach ($participantArray as $i => $participant) {
                            /**
                             * @var Team[] $participant
                             */
                            if (in_array($game->home_team_id, [$participant['home']->id, $participant['guest']->id], true)) {
                                $inArray = true;

                                if ($game->home_team_id === $participant['home']->id) {
                                    $formatScore = 'home';
                                } else {
                                    $formatScore = 'guest';
                                }

                                $participantArray[$i]['game'][] = Html::a(
                                    $game->formatScore($formatScore),
                                    ['game/view', 'id' => $game->id]
                                );
                            }
                        }

                        if (false === $inArray) {
                            $participantArray[] = [
                                'home' => $game->homeTeam,
                                'guest' => $game->guestTeam,
                                'game' => [
                                    Html::a(
                                        $game->formatScore(),
                                        ['game/view', 'id' => $game->id]
                                    )
                                ],
                            ];
                        }
                    }

                    $playoffArray[] = [
                        'stage' => $stage,
                        'participant' => $participantArray,
                    ];
                }
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.league.playoff.title'));
        return $this->render('qualification', [
            'qualificationArray' => $playoffArray,
            'roundArray' => $this->getRoundLinksArray($seasonId),
            'seasonArray' => $this->getSeasonArray(),
            'seasonId' => $seasonId,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionStatistics($id = StatisticType::TEAM_NO_PASS): string
    {
        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);

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

        if (1 === $statisticType->statistic_chapter_id) {
            $query = StatisticTeam::find()
                ->where(['tournament_type_id' => TournamentType::LEAGUE])
                ->orderBy([$statisticType->select_field => SORT_DESC]);
        } else {
            $query = StatisticPlayer::find()
                ->where([
                    'tournament_type_id' => TournamentType::LEAGUE,
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

        $this->setSeoTitle(Yii::t('frontend', 'controllers.league.statistics.title'));

        return $this->render('statistics', [
            'dataProvider' => $dataProvider,
            'myTeam' => $this->myTeam,
            'seasonId' => $seasonId,
            'statisticType' => $statisticType,
            'statisticTypeArray' => StatisticChapter::selectOptions(),
        ]);
    }
}
