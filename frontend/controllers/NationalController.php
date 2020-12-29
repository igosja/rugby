<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Achievement;
use common\models\db\Finance;
use common\models\db\Game;
use common\models\db\History;
use common\models\db\Mood;
use common\models\db\National;
use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\Position;
use common\models\db\Season;
use Exception;
use frontend\models\forms\NationalPlayer;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class NationalController
 * @package frontend\controllers
 */
class NationalController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'attitude-national',
                    'fire',
                    'player',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'attitude-national',
                            'fire',
                            'player',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return Response
     * @throws \yii\db\Exception
     */
    public function actionAttitudeNational(int $id): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['national/view', 'id' => $id]);
        }

        if (!$this->myTeam->load(Yii::$app->request->post())) {
            return $this->redirect(['national/view', 'id' => $id]);
        }

        $this->myTeam->save(true, ['team_attitude_national', 'team_attitude_u21', 'team_attitude_u19']);
        return $this->redirect(['national/view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionView(int $id): string
    {
        $national = $this->getNational($id);

        $notificationArray = [];
        if ($this->myNationalOrVice && $id === $this->myNationalOrVice->id) {
            $notificationArray = $this->getNotificationArray();
        }

        $query = Player::find()
            ->joinWith(['playerPositions'])
            ->where(['national_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['age' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['age' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'game_row' => [
                        'asc' => ['game_row' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['game_row' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'position' => [
                        'asc' => ['position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'physical' => [
                        'asc' => [$national->myTeam() ? 'physical_id' : 'position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => [$national->myTeam() ? 'physical_id' : 'position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'power_nominal' => [
                        'asc' => ['power_nominal' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['power_nominal' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'power_real' => [
                        'asc' => [$national->myTeam() ? 'power_real' : 'power_nominal' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => [$national->myTeam() ? 'power_real' : 'power_nominal' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['price' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['price' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'squad' => [
                        'asc' => ['squad_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['squad_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'tire' => [
                        'asc' => [$national->myTeam() ? 'tire' : 'position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => [$national->myTeam() ? 'tire' : 'position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['position' => SORT_ASC],
            ],
        ]);

        $this->setSeoTitle($national->fullName() . '. Профиль сборной');

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'notificationArray' => $notificationArray,
            'national' => $national,
        ]);
    }

    /**
     * @param int $id
     * @return National
     * @throws NotFoundHttpException
     */
    public function getNational(int $id): National
    {
        /**
         * @var National $national
         */
        $national = National::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($national);

        return $national;
    }

    /**
     * @return array|Response
     * @throws Exception
     */
    public function getNotificationArray()
    {
        if (!$this->myNationalOrVice) {
            return [];
        }

        $result = [];

        /**
         * @var Game $closestGame
         */
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
                $result[] = '<span class="font-red">Вы не отправили состав на ближайший матч своей команды.</span> ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup-national/view', 'id' => $closestGame->id]
                    );
            }

            if (($closestGame->home_national_id === $this->myNationalOrVice->id && Mood::SUPER === $closestGame->home_mood_id) ||
                ($closestGame->guest_national_id === $this->myNationalOrVice->id && Mood::SUPER === $closestGame->guest_mood_id)) {
                $result[] = 'В ближайшем матче ваша команда будет использовать <span class="strong font-green">супер</span>. ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup-national/view', 'id' => $closestGame->id]
                    );
            }

            if (($closestGame->home_national_id === $this->myNationalOrVice->id && Mood::REST === $closestGame->home_mood_id) ||
                ($closestGame->guest_national_id === $this->myNationalOrVice->id && Mood::REST === $closestGame->guest_mood_id)) {
                $result[] = 'В ближайшем матче ваша команда будет использовать <span class="strong font-red">отдых</span>. ' . Html::a(
                        '<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>',
                        ['lineup-national/view', 'id' => $closestGame->id]
                    );
            }
        }

        return $result;
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGame(int $id): string
    {
        $national = $this->getNational($id);

        $seasonId = Yii::$app->request->get('season_id', $this->season->id);

        $query = Game::find()
            ->joinWith(['schedule'])
            ->where(['or', ['home_national_id' => $id], ['guest_national_id' => $id]])
            ->andWhere(['season_id' => $seasonId])
            ->orderBy(['date' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $totalPoint = 0;
        foreach ($dataProvider->models as $game) {
            /**
             * @var Game $game
             */
            if (!$game->played) {
                continue;
            }
            $totalPoint += (int)$game->gamePlusMinus($national);
        }

        $this->setSeoTitle($national->fullName() . '. Матчи сборной');

        return $this->render('game', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'national' => $national,
            'totalPoint' => $totalPoint,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEvent(int $id): string
    {
        $national = $this->getNational($id);

        $seasonId = Yii::$app->request->get('season_id', $this->season->id);

        $query = History::find()
            ->where(['national_id' => $id, 'season_id' => $seasonId])
            ->orderBy(['history_id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
        ]);

        $this->setSeoTitle($national->fullName() . '. События сборной');

        return $this->render('event', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'national' => $national,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFinance(int $id): string
    {
        $national = $this->getNational($id);

        $seasonId = Yii::$app->request->get('season_id', $this->season->id);

        $query = Finance::find()
            ->where(['national_id' => $id])
            ->andWhere(['season_id' => $seasonId])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle($national->fullName() . '. Финансы сборной');
        return $this->render('finance', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'national' => $national,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAchievement(int $id): string
    {
        $national = $this->getNational($id);

        $query = Achievement::find()
            ->where(['national_id' => $id])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle($national->fullName() . '. Достижения сборной');
        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
            'national' => $national,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTrophy(int $id): string
    {
        $national = $this->getNational($id);

        $query = Achievement::find()
            ->where(['national_id' => $id, 'place' => 1])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle($national->fullName() . '. Трофеи сборной');
        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
            'national' => $national,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionPlayer(int $id)
    {
        if (!$this->myNational) {
            $this->forbiddenRole();
        }

        $national = $this->getNational($id);
        if ($this->myNational->id !== $national->id) {
            $this->forbiddenRole();
        }

        $model = new NationalPlayer(['national' => $national]);
        if ($model->savePlayer()) {
            $this->setSuccessFlash();
            return $this->refresh();
        }

        $propArray = $hookerArray = $lockArray = $flankerArray = $eightArray = $scrumHalfArray = $flyHalfArray = $wingArray = $centreArray = $fullBackArray = [];
        $playersData = [
            ['position' => Position::PROP, 'limit' => 30, 'array' => 'propArray'],
            ['position' => Position::HOOKER, 'limit' => 15, 'array' => 'hookerArray'],
            ['position' => Position::LOCK, 'limit' => 30, 'array' => 'lockArray'],
            ['position' => Position::FLANKER, 'limit' => 30, 'array' => 'flankerArray'],
            ['position' => Position::EIGHT, 'limit' => 15, 'array' => 'eightArray'],
            ['position' => Position::SCRUM_HALF, 'limit' => 15, 'array' => 'scrumHalfArray'],
            ['position' => Position::FLY_HALF, 'limit' => 15, 'array' => 'flyHalfArray'],
            ['position' => Position::WING, 'limit' => 30, 'array' => 'wingArray'],
            ['position' => Position::CENTRE, 'limit' => 30, 'array' => 'centreArray'],
            ['position' => Position::FULL_BACK, 'limit' => 15, 'array' => 'fullBackArray'],
        ];

        foreach ($playersData as $playerData) {
            $array = $playerData['array'];
            $$array = Player::find()
                ->where([
                    'country_id' => $national->federation->country_id,
                    'national_id' => [null, $national->id]
                ])
                ->andWhere([
                    'id' => PlayerPosition::find()
                        ->select(['player_id'])
                        ->where(['position_id' => $playerData['position']])
                ])
                ->andWhere(['!=', 'team_id', 0])
                ->orderBy(['power_nominal_s' => SORT_DESC, 'id' => SORT_DESC])
                ->limit($playerData['limit'])
                ->all();
        }

        /**
         * @var Player[] $playerArray
         */
        $playerArray = Player::find()
            ->where(['national_id' => $national->id])
            ->orderBy(['power_nominal_s' => SORT_DESC])
            ->all();
        foreach ($playerArray as $player) {
            if (Position::PROP === $player->playerPositions[0]->position_id) {
                $positionArray = $propArray;
            } elseif (Position::HOOKER === $player->playerPositions[0]->position_id) {
                $positionArray = $hookerArray;
            } elseif (Position::LOCK === $player->playerPositions[0]->position_id) {
                $positionArray = $lockArray;
            } elseif (Position::FLANKER === $player->playerPositions[0]->position_id) {
                $positionArray = $flankerArray;
            } elseif (Position::EIGHT === $player->playerPositions[0]->position_id) {
                $positionArray = $eightArray;
            } elseif (Position::SCRUM_HALF === $player->playerPositions[0]->position_id) {
                $positionArray = $scrumHalfArray;
            } elseif (Position::FLY_HALF === $player->playerPositions[0]->position_id) {
                $positionArray = $flyHalfArray;
            } elseif (Position::WING === $player->playerPositions[0]->position_id) {
                $positionArray = $wingArray;
            } elseif (Position::CENTRE === $player->playerPositions[0]->position_id) {
                $positionArray = $centreArray;
            } else {
                $positionArray = $fullBackArray;
            }

            $present = false;
            foreach ($positionArray as $position) {
                /**
                 * @var Player $position
                 */
                if ($position->id === $player->id) {
                    $present = true;
                }
            }

            if ($present) {
                continue;
            }

            $positionArray[] = $player;
            if (Position::PROP === $player->playerPositions[0]->position_id) {
                $propArray = $positionArray;
            } elseif (Position::HOOKER === $player->playerPositions[0]->position_id) {
                $hookerArray = $positionArray;
            } elseif (Position::LOCK === $player->playerPositions[0]->position_id) {
                $lockArray = $positionArray;
            } elseif (Position::FLANKER === $player->playerPositions[0]->position_id) {
                $flankerArray = $positionArray;
            } elseif (Position::EIGHT === $player->playerPositions[0]->position_id) {
                $eightArray = $positionArray;
            } elseif (Position::SCRUM_HALF === $player->playerPositions[0]->position_id) {
                $scrumHalfArray = $positionArray;
            } elseif (Position::FLY_HALF === $player->playerPositions[0]->position_id) {
                $flyHalfArray = $positionArray;
            } elseif (Position::WING === $player->playerPositions[0]->position_id) {
                $wingArray = $positionArray;
            } elseif (Position::CENTRE === $player->playerPositions[0]->position_id) {
                $centreArray = $positionArray;
            } else {
                $fullBackArray = $positionArray;
            }
        }

        $this->setSeoTitle('Изменение состава сборной');

        return $this->render('player', [
            'propArray' => $propArray,
            'hookerArray' => $hookerArray,
            'lockArray' => $lockArray,
            'flankerArray' => $flankerArray,
            'eightArray' => $eightArray,
            'scrumHalfArray' => $scrumHalfArray,
            'flyHalfArray' => $flyHalfArray,
            'wingArray' => $wingArray,
            'centreArray' => $centreArray,
            'fullBackArray' => $fullBackArray,
            'model' => $model,
            'national' => $national,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionFire($id)
    {
        $national = $this->getNational($id);
        if (!in_array($this->user->id, [$national->user_id, $national->vice_user_id], true)) {
            $this->setErrorFlash('Вы не занимаете руководящей должности в этой сборной');
            return $this->redirect(['view', 'id' => $id]);
        }

        if (!$national->vice_user_id) {
            $this->setErrorFlash('Нельзя отказаться от должности если в сборной нет заместителя');
            return $this->redirect(['view', 'id' => $id]);
        }

        if (Yii::$app->request->get('ok')) {
            if ($this->user->id === $national->user_id) {
                $national->fireUser();
            } elseif ($this->user->id === $national->vice_user_id) {
                $national->fireVice();
            }

            $this->setSuccessFlash('Вы успешно отказались от должности');
            return $this->redirect(['view', 'id' => $id]);
        }

        $this->setSeoTitle('Отказ от должности');
        return $this->render('fire', [
            'id' => $id,
            'national' => $national,
        ]);
    }
}
