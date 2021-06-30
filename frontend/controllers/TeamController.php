<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\ElectionNational;
use common\models\db\ElectionNationalApplication;
use common\models\db\ElectionNationalVice;
use common\models\db\ElectionNationalViceApplication;
use common\models\db\ElectionNationalViceVote;
use common\models\db\ElectionNationalVote;
use common\models\db\ElectionStatus;
use common\models\db\Federation;
use common\models\db\FriendlyInvite;
use common\models\db\FriendlyInviteStatus;
use common\models\db\Game;
use common\models\db\Loan;
use common\models\db\LoanVote;
use common\models\db\Logo;
use common\models\db\Mood;
use common\models\db\National;
use common\models\db\NationalType;
use common\models\db\Season;
use common\models\db\Team;
use common\models\db\Transfer;
use common\models\db\TransferVote;
use Exception;
use frontend\models\forms\ChangeMyTeam;
use frontend\models\forms\TeamLogo;
use frontend\models\preparers\AchievementPrepare;
use frontend\models\preparers\FinancePrepare;
use frontend\models\preparers\GamePrepare;
use frontend\models\preparers\HistoryPrepare;
use frontend\models\preparers\LoanPrepare;
use frontend\models\preparers\PlayerPrepare;
use frontend\models\preparers\TeamPrepare;
use frontend\models\preparers\TransferPrepare;
use frontend\models\queries\TeamQuery;
use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class TeamController
 * @package frontend\controllers
 */
class TeamController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = TeamPrepare::getTeamGroupDataProvider();

        $this->setSeoTitle(Yii::t('frontend', 'controllers.team.index.title'));
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return Response
     */
    public function actionChangeMyTeam(): Response
    {
        $model = new ChangeMyTeam();
        if ($model->load(Yii::$app->request->post(), '')) {
            $model->changeMyTeam();
        }

        return $this->redirect(['view']);
    }

    /**
     * @param int|null $id
     * @return string|Response
     * @throws Exception
     */
    public function actionView(int $id = null)
    {
        if (!$id && $this->myTeamOrVice) {
            return $this->redirect(['view', 'id' => $this->myTeamOrVice->id]);
        }

        if (!$id) {
            return $this->redirect(['team-request/index']);
        }

        $team = $this->getTeam($id);

        $notificationArray = [];
        if ($this->myTeam && $id === $this->myTeam->id) {
            $notificationArray = $this->notificationArray();
        }

        $dataProvider = PlayerPrepare::getPlayerTeamDataProvider($team);

        $this->setSeoTitle($team->name . '. ' . Yii::t('frontend', 'controllers.team.view.title'));
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'notificationArray' => $notificationArray,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAchievement(int $id): string
    {
        $team = $this->getTeam($id);

        $dataProvider = AchievementPrepare::getTeamAchievementDataProvider($team->id);

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.achievement.title'));
        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDeal(int $id): string
    {
        $team = $this->getTeam($id);

        $dataProviderTransferFrom = TransferPrepare::getTeamSellerDataProvider($team->id);
        $dataProviderTransferTo = TransferPrepare::getTeamBuyerDataProvider($team->id);
        $dataProviderLoanFrom = LoanPrepare::getTeamSellerDataProvider($team->id);
        $dataProviderLoanTo = LoanPrepare::getTeamBuyerDataProvider($team->id);

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.deal.title'));
        return $this->render('deal', [
            'dataProviderTransferFrom' => $dataProviderTransferFrom,
            'dataProviderTransferTo' => $dataProviderTransferTo,
            'dataProviderLoanFrom' => $dataProviderLoanFrom,
            'dataProviderLoanTo' => $dataProviderLoanTo,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFinance(int $id): string
    {
        $team = $this->getTeam($id);

        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $dataProvider = FinancePrepare::getTeamDataProvider($team->id, $seasonId);

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.finance.title'));
        return $this->render('finance', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGame(int $id): string
    {
        $team = $this->getTeam($id);

        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $dataProvider = GamePrepare::getTeamGameDataProvider($team->id, $seasonId);

        $totalPoint = 0;
        $totalGameResult = [
            'game' => 0,
            'win' => 0,
            'draw' => 0,
            'loose' => 0,
        ];
        foreach ($dataProvider->models as $game) {
            /**
             * @var Game $game
             */
            if (!$game->played) {
                continue;
            }
            $totalPoint += (int)$game->gamePlusMinus($team);
            $totalGameResult['game']++;
            if ($team->id === $game->home_team_id) {
                if ($game->home_point > $game->guest_point) {
                    $totalGameResult['win']++;
                } elseif ($game->home_point === $game->guest_point) {
                    $totalGameResult['draw']++;
                } else {
                    $totalGameResult['loose']++;
                }
            } else {
                if ($game->guest_point > $game->home_point) {
                    $totalGameResult['win']++;
                } elseif ($game->guest_point === $game->home_point) {
                    $totalGameResult['draw']++;
                } else {
                    $totalGameResult['loose']++;
                }
            }
        }

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.game.title'));
        return $this->render('game', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'team' => $team,
            'totalGameResult' => $totalGameResult,
            'totalPoint' => $totalPoint,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionHistory(int $id): string
    {
        $team = $this->getTeam($id);

        $seasonId = Yii::$app->request->get('seasonId', $this->season->id);
        $dataProvider = HistoryPrepare::getTeamDataProvider($team->id, $seasonId);

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.history.title'));
        return $this->render('history', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTrophy(int $id): string
    {
        $team = $this->getTeam($id);

        $dataProvider = AchievementPrepare::getTeamTrophyDataProvider($team->id);

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.trophy.title'));
        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionStatistics(int $id): string
    {
        $team = $this->getTeam($id);

        $team = Team::find()
            ->where(['id' => $team->id])
            ->limit(1)
            ->one();

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.statistics.title'));
        return $this->render('statistics', [
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionLogo(int $id)
    {
        $team = $this->getTeam($id);

        $model = new TeamLogo($team->id);
        if ($model->upload()) {
            $this->setSuccessFlash();
            return $this->refresh();
        }

        $logoArray = Logo::find()->all();

        $this->setSeoTitle($team->fullName() . '. ' . Yii::t('frontend', 'controllers.team.logo.title'));

        return $this->render('logo', [
            'logoArray' => $logoArray,
            'model' => $model,
            'team' => $team,
        ]);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function notificationArray(): array
    {
        if (!$this->myTeam) {
            return [];
        }

        $user = $this->user;

        $result = [];

        /**
         * @var Game $closestGame
         */
        $closestGame = Game::find()
            ->joinWith(['schedule'])
            ->where([
                'or',
                ['home_team_id' => $this->myTeam->id],
                ['guest_team_id' => $this->myTeam->id],
            ])
            ->andWhere(['played' => null])
            ->orderBy(['date' => SORT_ASC])
            ->limit(1)
            ->one();
        if ($closestGame) {
            if (($closestGame->home_team_id === $this->myTeam->id && !$closestGame->home_mood_id) ||
                ($closestGame->guest_team_id === $this->myTeam->id && !$closestGame->guest_mood_id)) {
                $result[] = Yii::t('frontend', 'controllers.team.notification.lineup') . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup/view', 'id' => $closestGame->id]
                    );
            }

            if (($closestGame->home_team_id === $this->myTeam->id && Mood::SUPER === $closestGame->home_mood_id) ||
                ($closestGame->guest_team_id === $this->myTeam->id && Mood::SUPER === $closestGame->guest_mood_id)) {
                $result[] = Yii::t('frontend', 'controllers.team.notification.super') . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup/view', 'id' => $closestGame->id]
                    );
            }

            if (($closestGame->home_team_id === $this->myTeam->id && Mood::REST === $closestGame->home_mood_id) ||
                ($closestGame->guest_team_id === $this->myTeam->id && Mood::REST === $closestGame->guest_mood_id)) {
                $result[] = Yii::t('frontend', 'controllers.team.notification.rest') . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup/view', 'id' => $closestGame->id]
                    );
            }
        }

        if ($user->isVip() && $user->date_vip < time() + 604800) {
            $result[] = Yii::t('frontend', 'controllers.team.notification.vip') . Html::a(
                    '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                    ['store/index']
                );
        }

        if ($this->myTeam->free_base_number) {
            $result[] = Yii::t('frontend', 'controllers.team.notification.base') . Html::a(
                    '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                    ['base-free/view']
                );
        }

        $friendlyInvite = FriendlyInvite::find()
            ->where([
                'guest_team_id' => $this->myTeam->id,
                'friendly_invite_status_id' => FriendlyInviteStatus::NEW_ONE,
            ])
            ->orderBy(['schedule_id' => SORT_ASC])
            ->limit(1)
            ->one();
        if ($friendlyInvite) {
            $result[] = Yii::t('frontend', 'controllers.team.notification.friendly') . Html::a(
                    '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                    ['friendly/view', 'id' => $friendlyInvite->schedule_id]
                );
        }

        $federation = $this->myTeam->stadium->city->country->federation;

        if ($this->season->id > 1) {
            $national = National::find()
                ->where(['federation_id' => $federation->id, 'national_type_id' => NationalType::MAIN])
                ->limit(1)
                ->one();

            if ($national && !$national->user_id && !$national->vice_user_id) {
                $electionNational = ElectionNational::find()
                    ->where([
                        'federation_id' => $federation->id,
                        'national_type_id' => NationalType::MAIN,
                        'election_status_id' => [
                            ElectionStatus::CANDIDATES,
                            ElectionStatus::OPEN,
                        ],
                    ])
                    ->limit(1)
                    ->one();

                if (!$electionNational) {
                    $electionNational = new ElectionNational();
                    $electionNational->election_status_id = ElectionStatus::CANDIDATES;
                    $electionNational->federation_id = $federation->id;
                    $electionNational->national_type_id = NationalType::MAIN;
                    $electionNational->save();
                }

                if (ElectionStatus::CANDIDATES === $electionNational->election_status_id) {
                    $electionNationalApplication = ElectionNationalApplication::find()
                        ->where([
                            'election_national_id' => $electionNational->id,
                            'user_id' => $this->user->id,
                        ])
                        ->count();
                    if ($electionNationalApplication) {
                        $result[] = Yii::t('frontend', 'controllers.team.notification.coach.candidate') . Html::a(
                                '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                ['national-election/application']
                            );
                    } else {
                        $result[] = Yii::t('frontend', 'controllers.team.notification.coach.application') . Html::a(
                                '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                ['national-election/application']
                            );
                    }
                } elseif (ElectionStatus::OPEN === $electionNational->election_status_id) {
                    $electionNationalVote = ElectionNationalVote::find()
                        ->where([
                            'election_national_application_id' => ElectionNationalApplication::find()
                                ->select(['id'])
                                ->where(['election_national_id' => $electionNational->id]),
                            'user_id' => $this->user->id,
                        ])
                        ->count();

                    if (!$electionNationalVote) {
                        Yii::$app->controller->redirect(['national-election/poll']);
                    }

                    $result[] = Yii::t('frontend', 'controllers.team.notification.coach.election') . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['national-election/view']
                        );
                }
            }

            if ($national && $national->user_id && !$national->vice_user_id) {
                $electionNationalVice = ElectionNationalVice::find()
                    ->where([
                        'federation_id' => $federation->id,
                        'national_type_id' => NationalType::MAIN,
                        'election_status_id' => [
                            ElectionStatus::CANDIDATES,
                            ElectionStatus::OPEN,
                        ],
                    ])
                    ->limit(1)
                    ->one();

                if (!$electionNationalVice) {
                    $electionNationalVice = new ElectionNationalVice();
                    $electionNationalVice->election_status_id = ElectionStatus::CANDIDATES;
                    $electionNationalVice->federation_id = $federation->id;
                    $electionNationalVice->national_type_id = NationalType::MAIN;
                    $electionNationalVice->save();
                }

                if (ElectionStatus::CANDIDATES === $electionNationalVice->election_status_id) {
                    $electionNationalViceApplication = ElectionNationalViceApplication::find()
                        ->where([
                            'election_national_vice_id' => $electionNationalVice->id,
                            'user_id' => $this->user->id,
                        ])
                        ->count();
                    if ($electionNationalViceApplication) {
                        $result[] = Yii::t('frontend', 'controllers.team.notification.vice.candidate') . Html::a(
                                '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                ['national-election-vice/application']
                            );
                    } else {
                        $result[] = Yii::t('frontend', 'controllers.team.notification.vice.application') . Html::a(
                                '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                ['national-election-vice/application']
                            );
                    }
                } elseif (ElectionStatus::OPEN === $electionNationalVice->election_status_id) {
                    $electionNationalVote = ElectionNationalViceVote::find()
                        ->where([
                            'election_national_vice_application_id' => ElectionNationalViceApplication::find()
                                ->select(['id'])
                                ->where(['election_national_vice_id' => $electionNationalVice->id]),
                            'user_id' => $this->user->id,
                        ])
                        ->count();

                    if (!$electionNationalVote) {
                        Yii::$app->controller->redirect(['national-election-vice/poll']);
                    }

                    $result[] = Yii::t('frontend', 'controllers.team.notification.vice.election') . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['national-election-vice/view']
                        );
                }
            }
        }

        /**
         * @var Federation[] $presidentFederationArray
         */
        $presidentFederationArray = Federation::find()
            ->all();

        if ($presidentFederationArray) {
            $presidentTeamIds = [];
            $presidentCountryIds = [];
            foreach ($presidentFederationArray as $federation) {
                $presidentCountryIds[] = $federation->country->id;
                foreach ($federation->country->cities as $city) {
                    foreach ($city->stadiums as $stadium) {
                        $presidentTeamIds[] = $stadium->team->id;
                    }
                }
            }

            /**
             * @var Transfer $transfer
             */
            $transfer = Transfer::find()
                ->joinWith(['player'])
                ->where([
                    'not',
                    [
                        'transfer.id' => TransferVote::find()
                            ->select(['transfer_id'])
                            ->where(['user_id' => $this->user->id])
                    ]
                ])
                ->andWhere(['voted' => null])
                ->andWhere(['not', ['ready' => null]])
                ->andWhere([
                    'or',
                    ['team_buyer_id' => $presidentTeamIds],
                    ['team_seller_id' => $presidentTeamIds],
                    ['country_id' => $presidentCountryIds],
                ])
                ->limit(1)
                ->one();
            if ($transfer) {
                $result[] = Yii::t('frontend', 'controllers.team.notification.deal') . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['transfer/view', 'id' => $transfer->id]
                    );
            } else {
                /**
                 * @var Loan $loan
                 */
                $loan = Loan::find()
                    ->joinWith(['player'])
                    ->where([
                        'not',
                        [
                            'loan.id' => LoanVote::find()
                                ->select(['loan_id'])
                                ->where(['user_id' => $this->user->id])
                        ]
                    ])
                    ->andWhere(['voted' => null])
                    ->andWhere(['not', ['ready' => null]])
                    ->andWhere([
                        'or',
                        ['team_buyer_id' => $presidentTeamIds],
                        ['team_seller_id' => $presidentTeamIds],
                        ['country_id' => $presidentCountryIds],
                    ])
                    ->limit(1)
                    ->one();
                if ($loan) {
                    $result[] = Yii::t('frontend', 'controllers.team.notification.deal') . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['loan/view', 'id' => $loan->id]
                        );
                } else {
                    $transfer = Transfer::find()
                        ->where([
                            'not',
                            [
                                'transfer.id' => TransferVote::find()
                                    ->select(['transfer_id'])
                                    ->where(['user_id' => $this->user->id])
                            ]
                        ])
                        ->andWhere(['voted' => null])
                        ->andWhere(['not', ['ready' => null]])
                        ->andWhere([
                            'transfer.id' => TransferVote::find()
                                ->select(['transfer_id'])
                                ->where(['<', 'rating', 0])
                        ])
                        ->limit(1)
                        ->one();
                    if ($transfer) {
                        $result[] = Yii::t('frontend', 'controllers.team.notification.deal.minus') . Html::a(
                                '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                ['transfer/view', 'id' => $transfer->id]
                            );
                    } else {
                        $loan = Loan::find()
                            ->where([
                                'not',
                                [
                                    'loan.id' => LoanVote::find()
                                        ->select(['loan_id'])
                                        ->where(['user_id' => $this->user->id])
                                ]
                            ])
                            ->andWhere(['voted' => null])
                            ->andWhere(['not', ['ready' => null]])
                            ->andWhere([
                                'loan.id' => LoanVote::find()
                                    ->select(['loan_id'])
                                    ->where(['<', 'rating', 0])
                            ])
                            ->limit(1)
                            ->one();
                        if ($loan) {
                            $result[] = Yii::t('frontend', 'controllers.team.notification.deal.minus') . Html::a(
                                    '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                    ['loan/view', 'id' => $loan->id]
                                );
                        }
                    }
                }
            }
        }

        if ($this->myNationalOrVice) {
            $closestGame = Game::find()
                ->joinWith(['schedule'])
                ->where([
                    'or',
                    ['home_national_id' => $this->myNationalOrVice->id],
                    ['guest_national_id' => $this->myNationalOrVice->id],
                ])
                ->andWhere(['played' => null])
                ->orderBy(['date' => SORT_ASC])
                ->limit(1)
                ->one();
            if ($closestGame) {
                if (($closestGame->home_national_id === $this->myNationalOrVice->id && !$closestGame->home_mood_id) ||
                    ($closestGame->guest_national_id === $this->myNationalOrVice->id && !$closestGame->guest_mood_id)) {
                    $result[] = Yii::t('frontend', 'controllers.team.notification.lineup.national') . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['lineup-national/view', 'id' => $closestGame->id]
                        );
                }
            }
        }

        return $result;
    }

    /**
     * @param int $teamId
     * @return Team
     * @throws NotFoundHttpException
     */
    private function getTeam(int $teamId): Team
    {
        $team = TeamQuery::getTeamById($teamId);
        $this->notFound($team);

        return $team;
    }
}
