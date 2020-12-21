<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Building;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Loan;
use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\PlayerSpecial;
use common\models\db\Position;
use common\models\db\Special;
use common\models\db\Training;
use common\models\db\Transfer;
use Exception;
use frontend\models\forms\TrainingForm;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class TrainingController
 * @package frontend\controllers
 */
class TrainingController extends AbstractController
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

        $model = new TrainingForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            return $this->redirect($model->redirectUrl());
        }

        $team = $this->myTeam;

        $trainingArray = Training::find()
            ->where(['team_id' => $team->id, 'ready' => null])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $query = Player::find()
            ->joinWith(['country', 'playerPositions'])
            ->where(['team_id' => $team->id, 'loan_team_id' => null]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['age' => SORT_ASC, 'player_id' => SORT_ASC],
                        'desc' => ['age' => SORT_DESC, 'player_id' => SORT_ASC],
                    ],
                    'country' => [
                        'asc' => ['country.name' => SORT_ASC],
                        'desc' => ['country.name' => SORT_DESC],
                    ],
                    'position' => [
                        'asc' => ['position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'power_nominal' => [
                        'asc' => ['power_nominal' => SORT_ASC, 'player_id' => SORT_ASC],
                        'desc' => ['power_nominal' => SORT_DESC, 'player_id' => SORT_ASC],
                    ],
                    'squad' => [
                        'asc' => ['squad_id' => SORT_ASC, 'player_id' => SORT_ASC],
                        'desc' => ['squad_id' => SORT_DESC, 'player_id' => SORT_ASC],
                    ],
                ],
                'defaultOrder' => ['position' => SORT_ASC],
            ],
        ]);

        $this->setSeoTitle($team->fullName() . '. Тренировка хоккеистов');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'onBuilding' => $this->isOnBuilding(),
            'team' => $team,
            'trainingArray' => $trainingArray,
        ]);
    }

    /**
     * @return bool
     */
    private function isOnBuilding(): bool
    {
        if (!$this->myTeam->buildingBase) {
            return false;
        }

        if (!in_array($this->myTeam->buildingBase->building_id, [Building::BASE, Building::TRAINING], true)) {
            return false;
        }

        return true;
    }

    /**
     * @return string|Response
     */
    public function actionTrain()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($this->isOnBuilding()) {
            $this->setErrorFlash('На базе сейчас идет строительство.');
            return $this->redirect(['index']);
        }

        $data = Yii::$app->request->get();

        $confirmData = [
            'position' => [],
            'power' => [],
            'special' => [],
            'price' => 0,
        ];

        $playerIdArray = [];

        if (isset($data['power'])) {
            foreach ($data['power'] as $playerId => $power) {
                /**
                 * @var Player $player
                 */
                $player = Player::find()
                    ->where(['id' => $playerId, 'team_id' => $team->id, 'loan_team_id' => null])
                    ->andWhere(['or', ['<', 'date_no_action', time()], ['date_no_action' => null]])
                    ->limit(1)
                    ->one();
                if (!$player) {
                    $this->setErrorFlash('Игрок выбран неправильно.');
                    return $this->redirect(['index']);
                }

                $transfer = Transfer::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($transfer) {
                    $this->setErrorFlash('Нельзя тренировать игрока, который выставлен на трансфер.');
                    return $this->redirect(['index']);
                }

                $loan = Loan::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($loan) {
                    $this->setErrorFlash('Нельзя тренировать игрока, который выставлен на арендный рынок.');
                    return $this->redirect(['index']);
                }

                $training = Training::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($training) {
                    $this->setErrorFlash('Одному игроку нельзя назначить несколько тренировок одновременно.');
                    return $this->redirect(['index']);
                }

                $confirmData['power'][] = [
                    'id' => $playerId,
                    'name' => $player->playerName(),
                ];

                $confirmData['price'] += $team->baseTraining->power_price;

                $playerIdArray[] = $playerId;
            }
        }

        if (isset($data['position'])) {
            foreach ($data['position'] as $playerId => $position) {
                $player = Player::find()
                    ->where(['id' => $playerId, 'team_id' => $team->id, 'loan_team_id' => null])
                    ->andWhere(['or', ['<', 'date_no_action', time()], ['date_no_action' => null]])
                    ->limit(1)
                    ->one();
                if (!$player) {
                    $this->setErrorFlash('Игрок выбран неправильно.');
                    return $this->redirect(['index']);
                }

                $playerPosition = PlayerPosition::find()
                    ->where(['player_id' => $playerId])
                    ->count();
                if (2 === $playerPosition) {
                    $this->setErrorFlash('Одному игроку нельзя натренировать больше одного совмещения.');
                    return $this->redirect(['index']);
                }

                $transfer = Transfer::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($transfer) {
                    $this->setErrorFlash('Нельзя тренировать игрока, который выставлен на трансфер.');
                    return $this->redirect(['index']);
                }

                $loan = Loan::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($loan) {
                    $this->setErrorFlash('Нельзя тренировать игрока, который выставлен на арендный рынок.');
                    return $this->redirect(['index']);
                }

                $training = Training::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($training) {
                    $this->setErrorFlash('Одному игроку нельзя назначить несколько тренировок одновременно.');
                    return $this->redirect(['index']);
                }

                /**
                 * @var Position $position
                 */
                $position = Position::find()
                    ->where(['id' => $position])
                    ->andWhere([
                        'not',
                        [
                            'id' => PlayerPosition::find()
                                ->select(['position_id'])
                                ->where(['player_id' => $playerId])
                        ]
                    ])
                    ->limit(1)
                    ->one();
                if (!$position) {
                    $this->setErrorFlash('Совмещение выбрано не правильно.');
                    return $this->redirect(['index']);
                }

                $confirmData['position'][] = [
                    'id' => $playerId,
                    'name' => $player->playerName(),
                    'position' => [
                        'id' => $position->id,
                        'name' => $position->text,
                    ],
                ];

                $confirmData['price'] += $team->baseTraining->position_price;

                $playerIdArray[] = $playerId;
            }
        }

        if (isset($data['special'])) {
            foreach ($data['special'] as $playerId => $special) {
                $player = Player::find()
                    ->where(['id' => $playerId, 'team_id' => $team->id, 'loan_team_id' => null])
                    ->andWhere(['or', ['<', 'date_no_action', time()], ['date_no_action' => null]])
                    ->limit(1)
                    ->one();
                if (!$player) {
                    $this->setErrorFlash('Игрок выбран неправильно.');
                    return $this->redirect(['index']);
                }

                $playerSpecial = PlayerSpecial::find()
                    ->where(['level' => Special::MAX_LEVEL, 'player_id' => $playerId])
                    ->count();
                if (Special::MAX_SPECIALS === $playerSpecial) {
                    $this->setErrorFlash('Игроку нельзя натренировать более 4 спецвозможностей.');
                    return $this->redirect(['index']);
                }

                $transfer = Transfer::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($transfer) {
                    $this->setErrorFlash('Нельзя тренировать игрока, который выставлен на трансфер.');
                    return $this->redirect(['index']);
                }

                $loan = Loan::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($loan) {
                    $this->setErrorFlash('Нельзя тренировать игрока, который выставлен на арендный рынок.');
                    return $this->redirect(['index']);
                }

                $training = Training::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($training) {
                    $this->setErrorFlash('Одному игроку нельзя назначить несколько тренировок одновременно.');
                    return $this->redirect(['index']);
                }

                $specialId = null;
                $playerSpecial = PlayerSpecial::find()
                    ->where(['player_id' => $playerId])
                    ->count();
                if (Special::MAX_SPECIALS === $playerSpecial) {
                    $specialId = PlayerSpecial::find()
                        ->select(['special_id'])
                        ->where(['player_id' => $playerId])
                        ->andWhere(['<', 'level', Special::MAX_LEVEL])
                        ->column();
                }

                /**
                 * @var Special $special
                 */
                $special = Special::find()
                    ->where(['id' => $special])
                    ->andWhere([
                        'not',
                        [
                            'id' => PlayerSpecial::find()
                                ->select(['special_id'])
                                ->where([
                                    'player_id' => $playerId,
                                    'level' => Special::MAX_LEVEL,
                                ])
                        ]
                    ])
                    ->andFilterWhere(['id' => $specialId])
                    ->limit(1)
                    ->one();
                if (!$special) {
                    $this->setErrorFlash('Спецвозможность выбрано не правильно.');
                    return $this->redirect(['index']);
                }

                $confirmData['special'][] = [
                    'id' => $playerId,
                    'name' => $player->playerName(),
                    'special' => [
                        'id' => $special->id,
                        'name' => $special->text,
                    ],
                ];

                $confirmData['price'] += $team->baseTraining->special_price;

                $playerIdArray[] = $playerId;
            }
        }

        if (count($confirmData['power']) > $team->availableTrainingPower()) {
            $this->setErrorFlash('У вас недостаточно баллов для тренировки.');
            return $this->redirect(['index']);
        }

        if (count($confirmData['position']) > $team->availableTrainingPosition()) {
            $this->setErrorFlash('У вас недостаточно совмещений для тренировки.');
            return $this->redirect(['index']);
        }

        if (count($confirmData['special']) > $team->availableTrainingSpecial()) {
            $this->setErrorFlash('У вас недостаточно спецвозможностей для тренировки.');
            return $this->redirect(['index']);
        }

        if (count($playerIdArray) !== count(array_unique($playerIdArray))) {
            $this->setErrorFlash('Одному игроку нельзя назначить несколько тренировок одновременно.');
            return $this->redirect(['index']);
        }

        if ($confirmData['price'] > $team->finance) {
            $this->setErrorFlash('У вас недостаточно денег для тренировки.');
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                foreach ($confirmData['power'] as $power) {
                    $model = new Training();
                    $model->player_id = $power['id'];
                    $model->is_power = true;
                    $model->season_id = $this->season->id;
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'finance_text_id' => FinanceText::OUTCOME_TRAINING_POWER,
                        'player_id' => $power['id'],
                        'team_id' => $team->id,
                        'value' => -$team->baseTraining->power_price,
                        'value_after' => $team->finance - $team->baseTraining->power_price,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance -= $team->baseTraining->power_price;
                    $team->save(true, ['finance']);
                }

                foreach ($confirmData['position'] as $playerId => $position) {
                    $model = new Training();
                    $model->player_id = $position['id'];
                    $model->position_id = $position['position']['id'];
                    $model->season_id = $this->season->id;
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'finance_text_id' => FinanceText::OUTCOME_TRAINING_POSITION,
                        'player_id' => $position['id'],
                        'team_id' => $team->id,
                        'value' => -$team->baseTraining->position_price,
                        'value_after' => $team->finance - $team->baseTraining->position_price,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance -= $team->baseTraining->position_price;
                    $team->save(true, ['finance']);
                }

                foreach ($confirmData['special'] as $playerId => $special) {
                    $model = new Training();
                    $model->player_id = $special['id'];
                    $model->season_id = $this->season->id;
                    $model->special_id = $special['special']['id'];
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'finance_text_id' => FinanceText::OUTCOME_TRAINING_SPECIAL,
                        'player_id' => $special['id'],
                        'team_id' => $team->id,
                        'value' => -$team->baseTraining->special_price,
                        'value_after' => $team->finance - $team->baseTraining->special_price,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance -= $team->baseTraining->special_price;
                    $team->save(true, ['finance']);
                }

                $this->setSuccessFlash('Тренировка успешно началась.');
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['index']);
        }

        $this->setSeoTitle($team->fullName() . '. Тренировка хоккеистов');

        return $this->render('train', [
            'confirmData' => $confirmData,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionCancel(int $id)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $training = Training::find()
            ->where(['id' => $id, 'ready' => null, 'team_id' => $team->id])
            ->limit(1)
            ->one();
        if (!$training) {
            $this->setErrorFlash('Тренировка выбрана неправильно.');
            return $this->redirect(['index']);
        }

        if ($training->is_power) {
            $price = $team->baseTraining->power_price;
        } elseif ($training->special_id) {
            $price = $team->baseTraining->special_price;
        } else {
            $price = $team->baseTraining->position_price;
        }

        if (Yii::$app->request->get('ok')) {
            try {
                if ($training->is_power) {
                    $text = FinanceText::INCOME_TRAINING_POWER;
                } elseif ($training->special_id) {
                    $text = FinanceText::INCOME_TRAINING_SPECIAL;
                } else {
                    $text = FinanceText::INCOME_TRAINING_POSITION;
                }

                $training->delete();

                Finance::log([
                    'finance_text_id' => $text,
                    'player_id' => $training->player_id,
                    'team_id' => $team->id,
                    'value' => $price,
                    'value_after' => $team->finance + $price,
                    'value_before' => $team->finance,
                ]);

                $team->finance += $price;
                $team->save(true, ['finance']);

                $this->setSuccessFlash('Тренировка успешно отменена.');
            } catch (Throwable $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['index']);
        }

        $this->setSeoTitle('Отмена тренировки. ' . $team->fullName());

        return $this->render('cancel', [
            'id' => $id,
            'price' => $price,
            'team' => $team,
            'training' => $training,
        ]);
    }
}
