<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Building;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Player;
use common\models\db\Scout;
use Exception;
use frontend\models\forms\ScoutForm;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class ScoutController
 * @package frontend\controllers
 */
class ScoutController extends AbstractController
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

        $model = new ScoutForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            return $this->redirect($model->redirectUrl());
        }

        $scoutArray = Scout::find()
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
                        'asc' => ['age' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['age' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['country.name' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['country.name' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'position' => [
                        'asc' => ['position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'power' => [
                        'asc' => ['power_nominal' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['power_nominal' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'squad' => [
                        'asc' => ['squad_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['squad_id' => SORT_DESC, 'id' => SORT_ASC],
                    ],
                ],
                'defaultOrder' => ['position' => SORT_ASC],
            ],
        ]);

        $this->setSeoTitle($team->fullName() . '. Изучение хоккеистов');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'onBuilding' => $this->isOnBuilding(),
            'scoutArray' => $scoutArray,
            'team' => $team,
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

        if (!in_array($this->myTeam->buildingBase->building_id, [Building::BASE, Building::SCOUT], true)) {
            return false;
        }

        return true;
    }

    /**
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionStudy()
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
            'style' => [],
            'price' => 0,
        ];

        if (isset($data['style'])) {
            foreach ($data['style'] as $playerId => $style) {
                /**
                 * @var Player $player
                 */
                $player = Player::find()
                    ->where(['id' => $playerId, 'team_id' => $team->id])
                    ->andWhere(['or', ['<', 'date_no_action', time()], ['date_no_action' => null]])
                    ->limit(1)
                    ->one();
                if (!$player) {
                    $this->setErrorFlash('Игрок выбран неправильно.');
                    return $this->redirect(['index']);
                }

                $scout = Scout::find()
                    ->where(['player_id' => $playerId, 'ready' => null])
                    ->count();
                if ($scout) {
                    $this->setErrorFlash('Одного игрока нельзя одновременно изучать несколько раз.');
                    return $this->redirect(['index']);
                }

                if (2 === $player->countScout()) {
                    $this->setErrorFlash('Игрок уже полностью изучен.');
                    return $this->redirect(['index']);
                }

                $confirmData['style'][] = [
                    'id' => $playerId,
                    'name' => $player->playerName(),
                ];

                $confirmData['price'] += $team->baseScout->my_style_price;
            }
        }

        if (count($confirmData['style']) > $team->availableScout()) {
            $this->setErrorFlash('У вас недостаточно стилей для изучения.');
            return $this->redirect(['index']);
        }

        if ($confirmData['price'] > $team->finance) {
            $this->setErrorFlash('У вас недостаточно денег для изучения.');
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                foreach ($confirmData['style'] as $style) {
                    $model = new Scout();
                    $model->player_id = $style['id'];
                    $model->is_style = true;
                    $model->season_id = $this->season->id;
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'finance_text_id' => FinanceText::OUTCOME_SCOUT_STYLE,
                        'player_id' => $style['id'],
                        'team_id' => $team->id,
                        'value' => -$team->baseScout->my_style_price,
                        'value_after' => $team->finance - $team->baseScout->my_style_price,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance -= $team->baseScout->my_style_price;
                    $team->save(true, ['finance']);
                }

                $this->setSuccessFlash('Изучение успешно началось.');
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['index']);
        }

        $this->setSeoTitle($team->fullName() . '. Изучение хоккеистов');

        return $this->render('study', [
            'confirmData' => $confirmData,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionCancel(int $id)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $scout = Scout::find()
            ->where(['id' => $id, 'ready' => null, 'team_id' => $team->id])
            ->limit(1)
            ->one();
        if (!$scout) {
            $this->setErrorFlash('Изучение выбрано неправильно.');
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                $scout->delete();

                Finance::log([
                    'finance_text_id' => FinanceText::INCOME_SCOUT_STYLE,
                    'player_id' => $scout->player_id,
                    'team_id' => $team->id,
                    'value' => $team->baseScout->my_style_price,
                    'value_after' => $team->finance + $team->baseScout->my_style_price,
                    'value_before' => $team->finance,
                ]);

                $team->finance += $team->baseScout->my_style_price;
                $team->save(true, ['finance']);

                $this->setSuccessFlash('Изучение успешно отменено.');
            } catch (Throwable $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['index']);
        }

        $this->setSeoTitle('Отмена изучения. ' . $team->fullName());

        return $this->render('cancel', [
            'id' => $id,
            'team' => $team,
            'scout' => $scout,
        ]);
    }
}
