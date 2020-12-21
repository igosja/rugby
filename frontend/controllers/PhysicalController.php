<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Building;
use common\models\db\Game;
use common\models\db\Physical;
use common\models\db\PhysicalChange;
use common\models\db\Player;
use common\models\db\Schedule;
use common\models\db\TournamentType;
use Exception;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Class PhysicalController
 * @package frontend\controllers
 */
class PhysicalController extends AbstractController
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

        /**
         * @var Physical[] $physicalArray
         */
        $physicalArray = ArrayHelper::map(Physical::find()->all(), 'id', 'name');

        /**
         * @var Schedule[] $scheduleArray
         */
        $scheduleArray = Schedule::find()
            ->where(['>', 'date', time()])
            ->andWhere(['not', ['tournament_type_id' => [TournamentType::CONFERENCE]]])
            ->groupBy(['date'])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $countSchedule = count($scheduleArray);

        $changeArray = [];
        /**
         * @var PhysicalChange[] $physicalChangeArray
         */
        $physicalChangeArray = PhysicalChange::find()
            ->where(['team_id' => $team->id])
            ->all();
        foreach ($physicalChangeArray as $item) {
            $changeArray[$item->player_id][$item->schedule_id] = 1;
        }

        $query = Player::find()
            ->joinWith(['playerPositions'])
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

        /**
         * @var Player[] $playerArray
         */
        $playerArray = $query->all();

        $physicalId = 0;
        $playerPhysicalArray = [];

        for ($i = 0; $i < $query->count(); $i++) {
            $class = '';
            $currentPlayerPhysicalArray = [];

            for ($j = 0; $j < $countSchedule; $j++) {
                if (0 === $j) {
                    $physicalId = $playerArray[$i]->physical_id;
                } else {
                    $physicalId++;
                }

                if (20 < $physicalId) {
                    $physicalId -= 20;
                }

                if (isset($changeArray[$playerArray[$i]->id][$scheduleArray[$j]->id])) {
                    $class = 'physical-change-cell physical-bordered';

                    $opposite = Physical::find()
                        ->where(['id' => $physicalId])
                        ->limit(1)
                        ->one();
                    $physicalId = $opposite->opposite_physical_id;
                } elseif (in_array($class, ['physical-change-cell physical-bordered', 'physical-change-cell physical-yellow', 'physical-bordered'])) {
                    $class = ($this->isOnBuilding() ? '' : 'physical-change-cell') . ' physical-yellow';
                } else {
                    $class = ($this->isOnBuilding() ? '' : 'physical-change-cell');
                }

                $currentPlayerPhysicalArray[] = [
                    'class' => $class,
                    'id' => $playerArray[$i]->id . '-' . $scheduleArray[$j]->id,
                    'physical_id' => $physicalId,
                    'physical_name' => $physicalArray[$physicalId],
                    'player_id' => $playerArray[$i]->id,
                    'schedule_id' => $scheduleArray[$j]->id,
                ];
            }

            $playerPhysicalArray[$playerArray[$i]->id] = $currentPlayerPhysicalArray;
        }

        $opponentArray = [];
        foreach ($scheduleArray as $key => $schedule) {
            /**
             * @var Game $game
             */
            $game = Game::find()
                ->where(['schedule_id' => $schedule->id])
                ->andWhere(['or', ['home_team_id' => $team->id], ['guest_team_id' => $team->id]])
                ->limit(1)
                ->one();
            if (!$game) {
                $opponentArray[$key] = '-';
            } elseif ($team->id === $game->guest_team_id) {
                $opponentArray[$key] = $game->homeTeam->name;
            } else {
                $opponentArray[$key] = $game->guestTeam->name;
            }
        }

        $this->setSeoTitle($team->fullName() . '. Центр физической подготовки');

        return $this->render('index', [
            'countSchedule' => $countSchedule,
            'dataProvider' => $dataProvider,
            'onBuilding' => $this->isOnBuilding(),
            'opponentArray' => $opponentArray,
            'playerPhysicalArray' => $playerPhysicalArray,
            'scheduleArray' => $scheduleArray,
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

        if (!in_array($this->myTeam->buildingBase->building_id, [Building::BASE, Building::PHYSICAL], true)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $tournament
     * @param string $stage
     * @param string $team
     */
    public function actionImage(string $tournament = null, string $stage = null, string $team = null): void
    {
        if ($tournament && $stage) {
            $text = $tournament . ', ' . $stage;
        } elseif ($team) {
            $text = $team;
        } else {
            $text = '-';
        }

        header("Content-type: image/png");

        $image = imagecreate(20, 250);
        imagecolorallocate($image, 0, 120, 45);
        $text_color = imagecolorallocate($image, 255, 255, 255);

        imagettftext($image, 11, 90, 15, 241, $text_color, Yii::getAlias('@webroot') . '/fonts/HelveticaNeue.ttf', $text);
        imagepng($image);
        imagedestroy($image);
    }

    /**
     * @param int $physicalId
     * @param int $playerId
     * @param int $scheduleId
     * @return array|Response
     * @throws Exception
     */
    public function actionChange(int $physicalId, int $playerId, int $scheduleId)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $changeStatus = true;

        $result = ['available' => 0, 'list' => []];

        $player = Player::find()
            ->where(['id' => $playerId, 'team_id' => $team->id, 'loan_team_id' => null])
            ->count();
        if ($player) {
            PhysicalChange::deleteAll([
                'and',
                ['player_id' => $playerId, 'season_id' => $this->season->id],
                ['>', 'schedule_id', $scheduleId]
            ]);

            $physicalChange = PhysicalChange::find()
                ->where([
                    'player_id' => $playerId,
                    'season_id' => $this->season->id,
                    'schedule_id' => $scheduleId,
                ])
                ->count();

            if ($physicalChange) {
                PhysicalChange::deleteAll([
                    'player_id' => $playerId,
                    'season_id' => $this->season->id,
                    'schedule_id' => $scheduleId,
                ]);
            } else {
                if ($team->availablePhysical()) {
                    $model = new PhysicalChange();
                    $model->schedule_id = $scheduleId;
                    $model->player_id = $playerId;
                    $model->season_id = $this->season->id;
                    $model->team_id = $team->id;
                    $model->save();
                } else {
                    $changeStatus = false;
                }
            }

            if ($changeStatus) {
                $countPrev = (int)PhysicalChange::find()
                    ->where(['player_id' => $playerId])
                    ->andWhere(['>', 'schedule_id', Schedule::find()
                        ->select(['id'])
                        ->where(['not', ['tournament_type_id' => [TournamentType::CONFERENCE]]])
                        ->andWhere(['>', 'date', time()])
                        ->orderBy(['id' => SORT_ASC])
                        ->limit(1)
                    ])
                    ->count();

                /**
                 * @var Physical[] $physical
                 */
                $physical = Physical::find()
                    ->orderBy(['id' => SORT_ASC])
                    ->all();

                $physicalArray = [];

                foreach ($physical as $item) {
                    $physicalArray[$item->id] = array(
                        'opposite' => (int)$item->opposite_physical_id,
                        'value' => (int)$item->name,
                    );
                }

                $scheduleArray = Schedule::find()
                    ->where(['>=', 'id', $scheduleId])
                    ->andWhere(['not', ['tournament_type_id' => [TournamentType::CONFERENCE]]])
                    ->groupBy(['date'])
                    ->orderBy(['id' => SORT_ASC])
                    ->all();

                for ($i = 0, $countSchedule = count($scheduleArray); $i < $countSchedule; $i++) {
                    if (0 === $i) {
                        if ($physicalChange && 0 === $countPrev) {
                            $class = '';
                        } elseif ($physicalChange && $countPrev) {
                            $class = 'physical-yellow';
                        } else {
                            $class = 'physical-bordered';
                        }

                        $physicalId = $physicalArray[$physicalId]['opposite'];
                    } else {
                        if ($physicalChange && 0 === $countPrev) {
                            $class = '';
                        } else {
                            $class = 'physical-yellow';
                        }

                        $physicalId++;

                        if (20 < $physicalId) {
                            $physicalId -= 20;
                        }
                    }

                    $result['list'][] = array(
                        'remove_class_1' => 'physical-bordered',
                        'remove_class_2' => 'physical-yellow',
                        'class' => $class,
                        'id' => $playerId . '-' . $scheduleArray[$i]->id,
                        'physical_id' => $physicalId,
                        'physical_value' => $physicalArray[$physicalId]['value'],
                    );
                }
            }
        }

        $result['available'] = $team->availablePhysical();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionClear(): Response
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $physicalChangeArray = PhysicalChange::find()
            ->where(['team_id' => $team->id])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select(['id'])
                    ->where(['>', 'date', time()])
            ])
            ->all();
        foreach ($physicalChangeArray as $physicalChange) {
            $physicalChange->delete();
        }

        return $this->redirect(['index']);
    }
}
