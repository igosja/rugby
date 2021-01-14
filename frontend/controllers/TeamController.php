<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\ElectionNational;
use common\models\db\ElectionNationalApplication;
use common\models\db\ElectionNationalVice;
use common\models\db\ElectionNationalViceApplication;
use common\models\db\ElectionNationalViceVote;
use common\models\db\ElectionNationalVote;
use common\models\db\ElectionPresident;
use common\models\db\ElectionPresidentApplication;
use common\models\db\ElectionPresidentVice;
use common\models\db\ElectionPresidentViceApplication;
use common\models\db\ElectionPresidentViceVote;
use common\models\db\ElectionPresidentVote;
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

        $this->setSeoTitle('Команды');
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

        $this->setSeoTitle($team->name . ' Профиль команды');
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

        $this->setSeoTitle($team->fullName() . ' - achievements');
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

        $this->setSeoTitle($team->fullName() . ' - deals');
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

        $this->setSeoTitle($team->fullName() . ' - finance');
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

        $this->setSeoTitle($team->fullName() . ' - games');
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

        $this->setSeoTitle($team->fullName() . ' - history');
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

        $this->setSeoTitle($team->fullName() . ' - trophies');
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

        $this->setSeoTitle($team->fullName() . ' - statistics');
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

        $this->setSeoTitle($team->fullName() . '. Загрузка эмблемы');

        return $this->render('logo', [
            'logoArray' => $logoArray,
            'model' => $model,
            'team' => $team,
        ]);
    }

    /**
     * @return array|Response
     * @throws Exception
     */
    public function notificationArray()
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
                $result[] = '<span class="font-red">Вы не отправили состав на ближайший матч своей команды.</span> ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup/view', 'id' => $closestGame->id]
                    );
            }

            if (($closestGame->home_team_id === $this->myTeam->id && Mood::SUPER === $closestGame->home_mood_id) ||
                ($closestGame->guest_team_id === $this->myTeam->id && Mood::SUPER === $closestGame->guest_mood_id)) {
                $result[] = 'В ближайшем матче ваша команда будет использовать <span class="strong font-green">супер</span>. ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup/view', 'id' => $closestGame->id]
                    );
            }

            if (($closestGame->home_team_id === $this->myTeam->id && Mood::REST === $closestGame->home_mood_id) ||
                ($closestGame->guest_team_id === $this->myTeam->id && Mood::REST === $closestGame->guest_mood_id)) {
                $result[] = 'В ближайшем матче ваша команда будет использовать <span class="strong font-red">отдых</span>. ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup/view', 'id' => $closestGame->id]
                    );
            }
        }

        if ($user->isVip() && $user->date_vip < time() + 604800) {
            $result[] = 'Ваш VIP-клуб заканчивается менее, чем через неделю - не забудьте продлить. ' . Html::a(
                    '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                    ['store/index']
                );
        }

        if ($this->myTeam->free_base_number) {
            $result[] = 'У вас есть бесплатные улучшения базы. ' . Html::a(
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
            $result[] = 'У вас есть новые приглашения сыграть товарищеский матч. ' . Html::a(
                    '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                    ['friendly/view', 'id' => $friendlyInvite->schedule_id]
                );
        }

        $federation = $this->myTeam->stadium->city->country->federation;

        if (!$federation->president_user_id && !$federation->vice_user_id) {
            $electionPresident = ElectionPresident::find()
                ->where([
                    'federation_id' => $federation->id,
                    'election_status_id' => [
                        ElectionStatus::CANDIDATES,
                        ElectionStatus::OPEN,
                    ],
                ])
                ->limit(1)
                ->one();

            if (!$electionPresident) {
                $electionPresident = new ElectionPresident();
                $electionPresident->election_status_id = ElectionStatus::CANDIDATES;
                $electionPresident->federation_id = $federation->id;
                $electionPresident->save();
            }

            if (ElectionStatus::CANDIDATES === $electionPresident->election_status_id) {
                $electionPresidentApplication = ElectionPresidentApplication::find()
                    ->where([
                        'election_president_id' => $electionPresident->id,
                        'user_id' => $this->user->id,
                    ])
                    ->count();
                if ($electionPresidentApplication) {
                    $result[] = 'Вы являетесь кандидатом на должность президента федерации. ' . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['president/application']
                        );
                } else {
                    $result[] = 'В вашей стране открыт прием заявок от кандидатов президентов федерации. ' . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['president/application']
                        );
                }
            } elseif (ElectionStatus::OPEN === $electionPresident->election_status_id) {
                $electionPresidentVote = ElectionPresidentVote::find()
                    ->where([
                        'election_president_application_id' => ElectionPresidentApplication::find()
                            ->select(['id'])
                            ->where(['election_president_id' => $electionPresident->id]),
                        'user_id' => $this->user->id,
                    ])
                    ->count();

                if (!$electionPresidentVote) {
                    Yii::$app->controller->redirect(['president/poll']);
                }

                $result[] = 'В вашей стране проходят выборы президента федерации. ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['president/view']
                    );
            }
        }

        if ($federation->president_user_id && !$federation->vice_user_id) {
            $electionPresidentVice = ElectionPresidentVice::find()
                ->where([
                    'federation_id' => $federation->id,
                    'election_status_id' => [
                        ElectionStatus::CANDIDATES,
                        ElectionStatus::OPEN,
                    ],
                ])
                ->limit(1)
                ->one();

            if (!$electionPresidentVice) {
                $electionPresidentVice = new ElectionPresidentVice();
                $electionPresidentVice->election_status_id = ElectionStatus::CANDIDATES;
                $electionPresidentVice->federation_id = $federation->id;
                $electionPresidentVice->save();
            }

            if (ElectionStatus::CANDIDATES === $electionPresidentVice->election_status_id) {
                $electionPresidentViceApplication = ElectionPresidentViceApplication::find()
                    ->where([
                        'election_president_vice_id' => $electionPresidentVice->id,
                        'user_id' => $this->user->id,
                    ])
                    ->count();
                if ($electionPresidentViceApplication) {
                    $result[] = 'Вы являетесь кандидатом на должность заместителя президента федерации. ' . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['president-vice/application']
                        );
                } else {
                    $result[] = 'В вашей стране открыт прием заявок от кандидатов заместителей президентов федерации. ' . Html::a(
                            '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                            ['president-vice/application']
                        );
                }
            } elseif (ElectionStatus::OPEN === $electionPresidentVice->election_status_id) {
                $electionPresidentVote = ElectionPresidentViceVote::find()
                    ->where([
                        'election_president_vice_application_id' => ElectionPresidentViceApplication::find()
                            ->select(['id'])
                            ->where(['election_president_vice_id' => $electionPresidentVice->id]),
                        'user_id' => $this->user->id,
                    ])
                    ->count();

                if (!$electionPresidentVote) {
                    Yii::$app->controller->redirect(['president-vice/poll']);
                }

                $result[] = 'В вашей стране проходят выборы заместителя президента федерации. ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['president-vice/view']
                    );
            }
        }

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
                        $result[] = 'Вы являетесь кандидатом на должность тренера национальной сборной. ' . Html::a(
                                '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                ['national-election/application']
                            );
                    } else {
                        $result[] = 'В вашей стране открыт прием заявок от кандидатов тренеров национальной сборной. ' . Html::a(
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

                    $result[] = 'В вашей стране проходят выборы тренера национальной сборной. ' . Html::a(
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
                        $result[] = 'Вы являетесь кандидатом на должность заместителя тренера национальной сборной. ' . Html::a(
                                '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                                ['national-election-vice/application']
                            );
                    } else {
                        $result[] = 'В вашей стране открыт прием заявок от кандидатов заместителей тренера национальной сборной. ' . Html::a(
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

                    $result[] = 'В вашей стране проходят выборы заместителя тренера национальной сборной. ' . Html::a(
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
            ->where([
                'or',
                ['president_user_id' => $this->user->id],
                ['vice_user_id' => $this->user->id],
            ])
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
                $result[] = 'У вас есть непроверенные сделки в вашей федерации. ' . Html::a(
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
                    $result[] = 'У вас есть непроверенные сделки в вашей федерации. ' . Html::a(
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
                        $result[] = 'У вас есть непроверенные сделки с отрицательными оценками. ' . Html::a(
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
                            $result[] = 'У вас есть непроверенные сделки с отрицательными оценками. ' . Html::a(
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
                    $result[] = '<span class="font-yellow">Вы не отправили состав на ближайший матч сборной.</span> ' . Html::a(
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
