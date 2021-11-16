<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\FriendlyInvite;
use common\models\db\FriendlyInviteStatus;
use common\models\db\FriendlyStatus;
use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\TournamentType;
use common\models\db\User;
use common\models\db\Weather;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class FormatController
 * @package frontend\controllers
 */
class FriendlyController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     */
    public function actionIndex()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $query = Schedule::find()
            ->where(['tournament_type_id' => TournamentType::FRIENDLY])
            ->andWhere(['>', 'date', time()])
            ->andWhere(['<', 'date', time() + 1209600])
            ->orderBy(['id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $scheduleStatusArray = [];
        foreach ($query->all() as $schedule) {
            /**
             * @var Schedule $schedule
             * @var Game $game
             */
            $game = Game::find()
                ->where(['schedule_id' => $schedule->id])
                ->andWhere([
                    'or',
                    ['home_team_id' => $this->myTeam->id],
                    ['guest_team_id' => $this->myTeam->id]
                ])
                ->limit(1)
                ->one();
            if ($game) {
                if ($game->home_team_id === $this->myTeam->id) {
                    $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.view.play') . $game->guestTeam->getTeamImageLink();
                } else {
                    $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.view.play') . $game->guestTeam->getTeamImageLink();
                }
                continue;
            }

            $invite = FriendlyInvite::find()
                ->where([
                    'schedule_id' => $schedule->id,
                    'guest_team_id' => $this->myTeam->id,
                    'friendly_invite_status_id' => FriendlyInviteStatus::NEW_ONE,
                ])
                ->count();
            if ($invite) {
                $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.index.invite');
                continue;
            }

            $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.index.no');
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.friendly.index.title'));

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'scheduleStatusArray' => $scheduleStatusArray,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        /**
         * @var Schedule $scheduleMain
         */
        $scheduleMain = Schedule::find()
            ->where(['tournament_type_id' => TournamentType::FRIENDLY, 'id' => $id])
            ->andWhere(['>', 'date', time()])
            ->andWhere(['<', 'date', time() + 1209600])
            ->limit(1)
            ->one();
        $this->notFound($scheduleMain);

        $canSendAndAccept = ($scheduleMain->date >= time() + 24 * 60 * 60);

        $query = Schedule::find()
            ->where(['tournament_type_id' => TournamentType::FRIENDLY])
            ->andWhere(['>', 'date', time()])
            ->andWhere(['<', 'date', time() + 1209600])
            ->orderBy(['id' => SORT_ASC]);
        $scheduleDataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $scheduleStatusArray = [];
        foreach ($query->all() as $schedule) {
            /**
             * @var Schedule $schedule
             * @var Game $game
             */
            $game = Game::find()
                ->where(['schedule_id' => $schedule->id])
                ->andWhere([
                    'or',
                    ['home_team_id' => $this->myTeam->id],
                    ['guest_team_id' => $this->myTeam->id]
                ])
                ->limit(1)
                ->one();
            if ($game) {
                if ($game->home_team_id === $this->myTeam->id) {
                    $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.view.play') . $game->guestTeam->getTeamImageLink();
                } else {
                    $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.view.play') . $game->homeTeam->getTeamImageLink();
                }
                continue;
            }

            $invite = FriendlyInvite::find()
                ->where([
                    'schedule_id' => $schedule->id,
                    'guest_team_id' => $this->myTeam->id,
                    'friendly_invite_status_id' => FriendlyInviteStatus::NEW_ONE,
                ])
                ->count();
            if ($invite) {
                $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.view.invite');
                continue;
            }

            $scheduleStatusArray[$schedule->id] = Yii::t('frontend', 'controllers.friendly.view.no');
        }

        $query = FriendlyInvite::find()
            ->where(['schedule_id' => $id, 'guest_team_id' => $team->id])
            ->orderBy(['id' => SORT_ASC]);
        $receivedDataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $query = FriendlyInvite::find()
            ->where(['schedule_id' => $id, 'home_team_id' => $team->id])
            ->orderBy(['id' => SORT_ASC]);
        $sentDataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $query = Team::find()
            ->andWhere(['!=', 'user_id', 0])
            ->andWhere(['!=', 'id', $team->id])
            ->andWhere(['!=', 'user_id', $this->user->id])
            ->andWhere(['!=', 'friendly_status_id', FriendlyStatus::NONE])
            ->andWhere([
                'not',
                [
                    'id' => Game::find()
                        ->select(['home_team_id'])
                        ->where(['schedule_id' => $id])
                ]
            ])
            ->andWhere([
                'not',
                [
                    'id' => Game::find()
                        ->select(['guest_team_id'])
                        ->where(['schedule_id' => $id])
                ]
            ])
            ->andWhere([
                'not',
                [
                    'id' => Game::find()
                        ->select(['guest_team_id'])
                        ->where(['home_team_id' => $this->myTeam->id])
                        ->andWhere([
                            'schedule_id' => Schedule::find()
                                ->select(['id'])
                                ->andWhere([
                                    'season_id' => Season::getCurrentSeason(),
                                    'tournament_type_id' => TournamentType::FRIENDLY,
                                ])
                        ])
                ]
            ])
            ->andWhere([
                'not',
                [
                    'id' => Game::find()
                        ->select(['home_team_id'])
                        ->where(['guest_team_id' => $this->myTeam->id])
                        ->andWhere([
                            'schedule_id' => Schedule::find()
                                ->select(['id'])
                                ->andWhere([
                                    'season_id' => Season::getCurrentSeason(),
                                    'tournament_type_id' => TournamentType::FRIENDLY,
                                ])
                        ])
                ]
            ])
            ->andWhere([
                'not',
                [
                    'id' => Team::find()
                        ->select(['id'])
                        ->where([
                            'user_id' => User::find()
                                ->select(['id'])
                                ->where(['referrer_user_id' => $this->user->id])
                        ])
                ]
            ])
            ->andWhere([
                'not',
                [
                    'id' => Team::find()
                        ->select(['id'])
                        ->where(['user_id' => $this->user->referrer_user_id])
                ]
            ])
            ->orderBy(['power_vs' => SORT_DESC]);

        $teamDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.friendly.view.title'));

        return $this->render('view', [
            'canSendAndAccept' => $canSendAndAccept,
            'receivedDataProvider' => $receivedDataProvider,
            'sentDataProvider' => $sentDataProvider,
            'scheduleStatusArray' => $scheduleStatusArray,
            'scheduleDataProvider' => $scheduleDataProvider,
            'team' => $team,
            'teamDataProvider' => $teamDataProvider,
        ]);
    }

    /**
     * @param int $id
     * @param int $teamId
     * @return Response
     * @throws Exception
     */
    public function actionSend(int $id, int $teamId): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $schedule = Schedule::find()
            ->where(['tournament_type_id' => TournamentType::FRIENDLY, 'id' => $id])
            ->andWhere(['>', 'date', time()])
            ->andWhere(['<', 'date', time() + 1209600])
            ->count();
        if (!$schedule) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.schedule'));
            return $this->redirect(['index']);
        }

        $game = Game::find()
            ->where(['schedule_id' => $id])
            ->andWhere([
                'or',
                ['guest_team_id' => $this->myTeam->id],
                ['home_team_id' => $this->myTeam->id]
            ])
            ->count();
        if ($game) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.game'));
            return $this->redirect(['view', 'id' => $id]);
        }

        /**
         * @var Team $team
         */
        $team = Team::find()
            ->where(['id' => $teamId])
            ->andWhere(['!=', 'user_id', 0])
            ->andWhere(['!=', 'user_id', $this->user->id])
            ->andWhere(['!=', 'id', $this->myTeam->id])
            ->andWhere(['!=', 'friendly_status_id', FriendlyStatus::NONE])
            ->limit(1)
            ->one();
        if (!$team) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.team'));
            return $this->redirect(['view', 'id' => $id]);
        }

        $invite = FriendlyInvite::find()
            ->where([
                'guest_team_id' => $teamId,
                'home_team_id' => $this->myTeam->id,
                'schedule_id' => $id,
            ])
            ->count();
        if ($invite) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.invite'));
            return $this->redirect(['view', 'id' => $id]);
        }

        $game = Game::find()
            ->where(['schedule_id' => $id])
            ->andWhere(['or', ['guest_team_id' => $teamId], ['home_team_id' => $teamId]])
            ->count();
        if ($game) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.game.opponent'));
            return $this->redirect(['view', 'id' => $id]);
        }

        $game = Game::find()
            ->where([
                'or',
                ['guest_team_id' => $teamId, 'home_team_id' => $this->myTeam->id],
                ['home_team_id' => $teamId, 'guest_team_id' => $this->myTeam->id]
            ])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select(['id'])
                    ->andWhere([
                        'season_id' => Season::getCurrentSeason(),
                        'tournament_type_id' => TournamentType::FRIENDLY,
                    ])
            ])
            ->count();
        if ($game) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.game.duplicate'));
            return $this->redirect(['view', 'id' => $id]);
        }

        if (FriendlyStatus::ALL === $team->friendly_status_id) {
            try {
                $model = new FriendlyInvite();
                $model->friendly_invite_status_id = FriendlyInviteStatus::ACCEPTED;
                $model->guest_team_id = $teamId;
                $model->guest_user_id = $team->user_id;
                $model->home_team_id = $this->myTeam->id;
                $model->home_user_id = $this->myTeam->user_id;
                $model->schedule_id = $id;
                $model->save();

                $model = new Game();
                $model->bonus_home = 0;
                $model->guest_team_id = $teamId;
                $model->home_team_id = $this->myTeam->id;
                $model->schedule_id = $id;
                $model->stadium_id = $this->myTeam->stadium_id;
                $model->weather_id = Weather::getRandomWeatherId();
                $model->save();

                FriendlyInvite::updateAll(
                    ['friendly_invite_status_id' => FriendlyInviteStatus::CANCELED],
                    [
                        'and',
                        ['!=', 'friendly_invite_status_id', FriendlyInviteStatus::ACCEPTED],
                        [
                            'or',
                            ['guest_team_id' => $this->myTeam->id],
                            ['home_team_id' => $this->myTeam->id],
                        ],
                    ]
                );

                FriendlyInvite::updateAll(
                    ['friendly_invite_status_id' => FriendlyInviteStatus::CANCELED],
                    [
                        'and',
                        ['!=', 'friendly_invite_status_id', FriendlyInviteStatus::ACCEPTED],
                        [
                            'or',
                            ['guest_team_id' => $teamId],
                            ['home_team_id' => $teamId],
                        ],
                    ]
                );
            } catch (Exception $e) {
                ErrorHelper::log($e);

                $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.game.catch'));
                return $this->redirect(['view', 'id' => $id]);
            }

            $this->setSuccessFlash(Yii::t('frontend', 'controllers.friendly.send.success.game'));
            return $this->redirect(['view', 'id' => $id]);
        }

        $invite = FriendlyInvite::find()
            ->where([
                'home_team_id' => $this->myTeam->id,
                'schedule_id' => $id,
                'friendly_invite_status_id' => FriendlyInviteStatus::NEW_ONE,
            ])
            ->count();
        if ($invite >= 5) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.5'));
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            $model = new FriendlyInvite();
            $model->friendly_invite_status_id = FriendlyInviteStatus::NEW_ONE;
            $model->guest_team_id = $teamId;
            $model->guest_user_id = $team->user_id;
            $model->home_team_id = $this->myTeam->id;
            $model->home_user_id = $this->myTeam->user_id;
            $model->schedule_id = $id;
            $model->save();
        } catch (Exception $e) {
            ErrorHelper::log($e);

            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.send.error.invite.catch'));
            return $this->redirect(['view', 'id' => $id]);
        }

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.friendly.send.success.invite'));
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionAccept(int $id): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }

        /**
         * @var FriendlyInvite $invite
         */
        $invite = FriendlyInvite::find()
            ->where(['id' => $id, 'guest_team_id' => $this->myTeam->id])
            ->limit(1)
            ->one();
        if (!$invite) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.accept.error.invite'));
            return $this->redirect(['index']);
        }

        if (FriendlyInviteStatus::ACCEPTED === $invite->friendly_invite_status_id) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.accept.error.accepted'));
            return $this->redirect(['view', 'id' => $invite->schedule_id]);
        }

        if (FriendlyInviteStatus::CANCELED === $invite->friendly_invite_status_id) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.accept.error.canceled'));
            return $this->redirect(['view', 'id' => $invite->schedule_id]);
        }

        $game = Game::find()
            ->where(['schedule_id' => $invite->schedule_id])
            ->andWhere([
                'or',
                ['guest_team_id' => $this->myTeam->id],
                ['home_team_id' => $this->myTeam->id]
            ])
            ->count();
        if ($game) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.accept.error.game'));
            return $this->redirect(['view', 'id' => $invite->schedule_id]);
        }

        $game = Game::find()
            ->where(['schedule_id' => $invite->schedule_id])
            ->andWhere([
                'or',
                ['guest_team_id' => $invite->home_team_id],
                ['home_team_id' => $invite->home_team_id]
            ])
            ->count();
        if ($game) {
            $invite->friendly_invite_status_id = FriendlyInviteStatus::CANCELED;
            $invite->save();

            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.accept.error.game.opponent'));
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            $invite->friendly_invite_status_id = FriendlyInviteStatus::ACCEPTED;
            $invite->save();

            $model = new Game();
            $model->bonus_home = 0;
            $model->guest_team_id = $invite->guest_team_id;
            $model->home_team_id = $invite->home_team_id;
            $model->schedule_id = $invite->schedule_id;
            $model->stadium_id = $invite->homeTeam->stadium_id;
            $model->weather_id = Weather::getRandomWeatherId();
            $model->save();

            FriendlyInvite::updateAll(
                ['friendly_invite_status_id' => FriendlyInviteStatus::CANCELED],
                [
                    'and',
                    ['!=', 'friendly_invite_status_id', FriendlyInviteStatus::ACCEPTED],
                    [
                        'or',
                        ['guest_team_id' => $invite->guest_team_id],
                        ['home_team_id' => $invite->guest_team_id],
                    ],
                ]
            );

            FriendlyInvite::updateAll(
                ['friendly_invite_status_id' => FriendlyInviteStatus::CANCELED],
                [
                    'and',
                    ['!=', 'friendly_invite_status_id', FriendlyInviteStatus::ACCEPTED],
                    [
                        'or',
                        ['guest_team_id' => $invite->home_team_id],
                        ['home_team_id' => $invite->home_team_id],
                    ],
                ]
            );
        } catch (Exception $e) {
            ErrorHelper::log($e);

            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.accept.error.catch'));
            return $this->redirect(['view', 'id' => $invite->schedule_id]);
        }

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.friendly.accept.error.success'));
        return $this->redirect(['view', 'id' => $invite->schedule_id]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionCancel(int $id): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $model = FriendlyInvite::find()
            ->where(['id' => $id])
            ->andWhere([
                'or',
                ['home_team_id' => $this->myTeam->id],
                ['guest_team_id' => $this->myTeam->id],
            ])
            ->limit(1)
            ->one();
        if (!$model) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.cancel.error.invite'));
            return $this->redirect(['index']);
        }

        if (FriendlyInviteStatus::ACCEPTED === $model->friendly_invite_status_id) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.cancel.error.accepted'));
            return $this->redirect(['view', 'id' => $model->schedule_id]);
        }

        if (FriendlyInviteStatus::CANCELED === $model->friendly_invite_status_id) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.friendly.cancel.error.canceled'));
            return $this->redirect(['view', 'id' => $model->schedule_id]);
        }

        $model->friendly_invite_status_id = FriendlyInviteStatus::CANCELED;
        $model->save();

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.friendly.cancel.success'));
        return $this->redirect(['view', 'id' => $model->schedule_id]);
    }

    /**
     * @return string|Response
     */
    public function actionStatus()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $model = $this->myTeam;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->refresh();
        }

        $friendlyStatusArray = ArrayHelper::map(
            FriendlyStatus::find()
                ->orderBy(['id' => SORT_ASC])
                ->all(),
            'id',
            'name'
        );

        $this->setSeoTitle(Yii::t('frontend', 'controllers.friendly.status.title'));
        return $this->render('status', [
            'friendlyStatusArray' => $friendlyStatusArray,
            'team' => $this->myTeam,
        ]);
    }
}
