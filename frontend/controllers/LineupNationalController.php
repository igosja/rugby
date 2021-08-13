<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Game;
use common\models\db\Lineup;
use common\models\db\Mood;
use common\models\db\Player;
use common\models\db\Position;
use common\models\db\Rudeness;
use common\models\db\Style;
use common\models\db\Tactic;
use common\models\db\TournamentType;
use frontend\models\forms\GameNationalSend;
use frontend\models\forms\GameSend;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class LineupController
 * @package frontend\controllers
 */
class LineupNationalController extends AbstractController
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
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        if (!$this->myNationalOrVice) {
            return $this->redirect(['team/view']);
        }

        $game = $this->getGame($id);

        $model = new GameNationalSend(['game' => $game, 'national' => $this->myNationalOrVice]);
        if ($model->saveLineup()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.lineup.success'));
            return $this->refresh();
        }

        $query = Game::find()
            ->where(['played' => null])
            ->andWhere([
                'or',
                ['guest_national_id' => $this->myNationalOrVice->id],
                ['home_national_id' => $this->myNationalOrVice->id]
            ])
            ->orderBy(['date' => SORT_ASC])
            ->limit(5);
        $gameDataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $query = Player::find()
            ->joinWith(['country', 'playerPositions'])
            ->andWhere(['national_id' => $this->myNationalOrVice->id]);
        $playerDataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'age' => [
                        'asc' => ['age' => SORT_ASC],
                        'desc' => ['age' => SORT_DESC],
                    ],
                    'country' => [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                    ],
                    'game_row' => [
                        'asc' => ['game_row' => SORT_ASC],
                        'desc' => ['game_row' => SORT_DESC],
                    ],
                    'position' => [
                        'asc' => ['position_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['position_id' => SORT_DESC, 'id' => SORT_DESC],
                    ],
                    'physical' => [
                        'asc' => ['physical_id' => SORT_ASC],
                        'desc' => ['physical_id' => SORT_DESC],
                    ],
                    'power_nominal' => [
                        'asc' => ['power_nominal' => SORT_ASC],
                        'desc' => ['power_nominal' => SORT_DESC],
                    ],
                    'power_real' => [
                        'asc' => ['power_real' => SORT_ASC],
                        'desc' => ['power_real' => SORT_DESC],
                    ],
                    'squad' => [
                        'asc' => ['squad_id' => SORT_ASC, 'position_id' => SORT_ASC],
                        'desc' => ['squad_id' => SORT_DESC, 'position_id' => SORT_ASC],
                    ],
                    'tire' => [
                        'asc' => ['tire' => SORT_ASC],
                        'desc' => ['tire' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['position' => SORT_ASC],
            ],
        ]);

        $player1array = [];
        $player2array = [];
        $player3array = [];
        $player4array = [];
        $player5array = [];
        $player6array = [];
        $player7array = [];
        $player8array = [];
        $player9array = [];
        $player10array = [];
        $player11array = [];
        $player12array = [];
        $player13array = [];
        $player14array = [];
        $player15array = [];
        /**
         * @var Player[] $playerArray
         */
        $playerArray = Player::find()
            ->where(['national_id' => $this->myNationalOrVice->id])
            ->orderBy(['power_real' => SORT_DESC])
            ->all();
        foreach ($playerArray as $player) {
            $player01 = clone $player;
            $player02 = clone $player;
            $player03 = clone $player;
            $player04 = clone $player;
            $player05 = clone $player;
            $player06 = clone $player;
            $player07 = clone $player;
            $player08 = clone $player;
            $player09 = clone $player;
            $player10 = clone $player;
            $player11 = clone $player;
            $player12 = clone $player;
            $player13 = clone $player;
            $player14 = clone $player;
            $player15 = clone $player;
            $pos01Coefficient = 0;
            $pos02Coefficient = 0;
            $pos03Coefficient = 0;
            $pos04Coefficient = 0;
            $pos05Coefficient = 0;
            $pos06Coefficient = 0;
            $pos07Coefficient = 0;
            $pos08Coefficient = 0;
            $pos09Coefficient = 0;
            $pos10Coefficient = 0;
            $pos11Coefficient = 0;
            $pos12Coefficient = 0;
            $pos13Coefficient = 0;
            $pos14Coefficient = 0;
            $pos15Coefficient = 0;
            $coefficientArray = [
                'forward' => [
                    Position::POS_01 => 'pos01Coefficient',
                    Position::POS_02 => 'pos02Coefficient',
                    Position::POS_03 => 'pos03Coefficient',
                    Position::POS_04 => 'pos04Coefficient',
                    Position::POS_05 => 'pos05Coefficient',
                    Position::POS_06 => 'pos06Coefficient',
                    Position::POS_07 => 'pos07Coefficient',
                    Position::POS_08 => 'pos08Coefficient',
                ],
                'back' => [
                    Position::POS_09 => 'pos09Coefficient',
                    Position::POS_10 => 'pos10Coefficient',
                    Position::POS_11 => 'pos11Coefficient',
                    Position::POS_12 => 'pos12Coefficient',
                    Position::POS_13 => 'pos13Coefficient',
                    Position::POS_14 => 'pos14Coefficient',
                    Position::POS_15 => 'pos15Coefficient',
                ],
            ];
            foreach ($player->playerPositions as $playerPosition) {
                $isForward = false;
                $isBack = false;
                if (array_key_exists($playerPosition->position_id, $coefficientArray['forward'])) {
                    $isForward = true;
                } else {
                    $isBack = true;
                }

                foreach ($coefficientArray as $chapter) {
                    foreach ($chapter as $position => $coefficient) {
                        if ($position === $playerPosition->position_id) {
                            if (1 > $$coefficient) {
                                $$coefficient = 1;
                            }
                        } elseif ('forward' === $chapter && $isForward) {
                            if (0.9 > $$coefficient) {
                                $$coefficient = 0.9;
                            }
                        } elseif ('back' === $chapter && $isBack) {
                            if (0.9 > $$coefficient) {
                                $$coefficient = 0.9;
                            }
                        } else {
                            if (0.8 > $$coefficient) {
                                $$coefficient = 0.8;
                            }
                        }
                    }
                }
            }

            $player01->power_real = round($player01->power_real * $pos01Coefficient);
            $player02->power_real = round($player02->power_real * $pos02Coefficient);
            $player03->power_real = round($player03->power_real * $pos03Coefficient);
            $player04->power_real = round($player04->power_real * $pos04Coefficient);
            $player05->power_real = round($player05->power_real * $pos05Coefficient);
            $player06->power_real = round($player06->power_real * $pos06Coefficient);
            $player07->power_real = round($player07->power_real * $pos07Coefficient);
            $player08->power_real = round($player08->power_real * $pos08Coefficient);
            $player09->power_real = round($player09->power_real * $pos09Coefficient);
            $player10->power_real = round($player10->power_real * $pos10Coefficient);
            $player11->power_real = round($player11->power_real * $pos11Coefficient);
            $player12->power_real = round($player12->power_real * $pos12Coefficient);
            $player13->power_real = round($player13->power_real * $pos13Coefficient);
            $player14->power_real = round($player14->power_real * $pos14Coefficient);
            $player15->power_real = round($player15->power_real * $pos15Coefficient);

            $player1array[] = $player01;
            $player2array[] = $player02;
            $player3array[] = $player03;
            $player4array[] = $player04;
            $player5array[] = $player05;
            $player6array[] = $player06;
            $player7array[] = $player07;
            $player8array[] = $player08;
            $player9array[] = $player09;
            $player10array[] = $player10;
            $player11array[] = $player11;
            $player12array[] = $player12;
            $player13array[] = $player13;
            $player14array[] = $player14;
            $player15array[] = $player15;
        }

        usort($player1array, [$this, 'sortLineup']);
        usort($player2array, [$this, 'sortLineup']);
        usort($player3array, [$this, 'sortLineup']);
        usort($player4array, [$this, 'sortLineup']);
        usort($player5array, [$this, 'sortLineup']);
        usort($player6array, [$this, 'sortLineup']);
        usort($player7array, [$this, 'sortLineup']);
        usort($player8array, [$this, 'sortLineup']);
        usort($player9array, [$this, 'sortLineup']);
        usort($player10array, [$this, 'sortLineup']);
        usort($player11array, [$this, 'sortLineup']);
        usort($player12array, [$this, 'sortLineup']);
        usort($player13array, [$this, 'sortLineup']);
        usort($player14array, [$this, 'sortLineup']);
        usort($player15array, [$this, 'sortLineup']);

        $player_1_id = $model->line[1] ?? 0;
        $player_2_id = $model->line[2] ?? 0;
        $player_3_id = $model->line[3] ?? 0;
        $player_4_id = $model->line[4] ?? 0;
        $player_5_id = $model->line[5] ?? 0;
        $player_6_id = $model->line[6] ?? 0;
        $player_7_id = $model->line[7] ?? 0;
        $player_8_id = $model->line[8] ?? 0;
        $player_9_id = $model->line[9] ?? 0;
        $player_10_id = $model->line[10] ?? 0;
        $player_11_id = $model->line[11] ?? 0;
        $player_12_id = $model->line[12] ?? 0;
        $player_13_id = $model->line[13] ?? 0;
        $player_14_id = $model->line[14] ?? 0;
        $player_15_id = $model->line[15] ?? 0;

        $noRest = null;
        $noSuper = null;
        if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) {
            $noSuper = Mood::SUPER;
            $noRest = Mood::REST;
        } elseif ($this->myNationalOrVice->mood_rest <= 0) {
            $noRest = Mood::REST;
        } elseif ($this->myNationalOrVice->mood_super <= 0) {
            $noSuper = Mood::SUPER;
        }
        $moodArray = Mood::find()
            ->andFilterWhere(['!=', 'id', $noSuper])
            ->andFilterWhere(['!=', 'id', $noRest])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $moodArray = ArrayHelper::map($moodArray, 'id', 'name');

        foreach ($moodArray as $moodId => $moodName) {
            if (Mood::SUPER === $moodId) {
                $moodArray[$moodId] = $moodName . ' (' . $this->myNationalOrVice->mood_super . ')';
            } elseif (Mood::REST === $moodId) {
                $moodArray[$moodId] = $moodName . ' (' . $this->myNationalOrVice->mood_rest . ')';
            }
        }

        $rudenessArray = ArrayHelper::map(Rudeness::find()->all(), 'id', 'name');
        $styleArray = ArrayHelper::map(Style::find()->all(), 'id', 'name');
        $tacticArray = ArrayHelper::map(Tactic::find()->all(), 'id', 'name');

        $this->setSeoTitle(Yii::t('frontend', 'controllers.lineup.view.title'));
        return $this->render('view', [
            'player_1_id' => $player_1_id,
            'player_2_id' => $player_2_id,
            'player_3_id' => $player_3_id,
            'player_4_id' => $player_4_id,
            'player_5_id' => $player_5_id,
            'player_6_id' => $player_6_id,
            'player_7_id' => $player_7_id,
            'player_8_id' => $player_8_id,
            'player_9_id' => $player_9_id,
            'player_10_id' => $player_10_id,
            'player_11_id' => $player_11_id,
            'player_12_id' => $player_12_id,
            'player_13_id' => $player_13_id,
            'player_14_id' => $player_14_id,
            'player_15_id' => $player_15_id,
            'player1array' => $player1array,
            'player2array' => $player2array,
            'player3array' => $player3array,
            'player4array' => $player4array,
            'player5array' => $player5array,
            'player6array' => $player6array,
            'player7array' => $player7array,
            'player8array' => $player8array,
            'player9array' => $player9array,
            'player10array' => $player10array,
            'player11array' => $player11array,
            'player12array' => $player12array,
            'player13array' => $player13array,
            'player14array' => $player14array,
            'player15array' => $player15array,
            'game' => $game,
            'gameDataProvider' => $gameDataProvider,
            'model' => $model,
            'moodArray' => $moodArray,
            'isVip' => $this->user->isVip(),
            'playerDataProvider' => $playerDataProvider,
            'rudenessArray' => $rudenessArray,
            'styleArray' => $styleArray,
            'tacticArray' => $tacticArray,
            'national' => $this->myNationalOrVice,
        ]);
    }

    /**
     * @param int $id
     * @return Game
     * @throws NotFoundHttpException
     */
    public function getGame(int $id): Game
    {
        /**
         * @var Game $game
         */
        $game = Game::find()
            ->where(['id' => $id, 'played' => null])
            ->andWhere([
                'or',
                ['guest_national_id' => $this->myNationalOrVice->id],
                ['home_national_id' => $this->myNationalOrVice->id]
            ])
            ->limit(1)
            ->one();
        $this->notFound($game);

        return $game;
    }

    /**
     * @param int $id
     * @return int[]
     * @throws NotFoundHttpException
     */
    public function actionTeamwork(int $id): array
    {
        $game = $this->getGame($id);

        /**
         * @var GameSend $model
         */
        $class = $this->getModel();
        $model = new $class;
        $model->load(Yii::$app->request->post());

        $result = [
            'power' => 0,
            'position' => 0,
            'lineup' => 0,
            'teamwork' => 0,
        ];

        $data = $model->line;

        if ($data) {
            $power = 0;
            $position = 0;
            $teamwork = 0;

            $positionF = [Position::PROP, Position::HOOKER, Position::LOCK, Position::FLANKER, Position::EIGHT];
            $positionB = [Position::SCRUM_HALF, Position::FULL_BACK, Position::CENTRE, Position::WING, Position::FULL_BACK];
            $positionCoefficients = [
                Position::POS_01 => [Position::PROP, $positionF],
                Position::POS_02 => [Position::HOOKER, $positionF],
                Position::POS_03 => [Position::PROP, $positionF],
                Position::POS_04 => [Position::LOCK, $positionF],
                Position::POS_05 => [Position::LOCK, $positionF],
                Position::POS_06 => [Position::FLANKER, $positionF],
                Position::POS_07 => [Position::FLANKER, $positionF],
                Position::POS_08 => [Position::EIGHT, $positionF],
                Position::POS_09 => [Position::SCRUM_HALF, $positionB],
                Position::POS_10 => [Position::FLY_HALF, $positionB],
                Position::POS_11 => [Position::WING, $positionB],
                Position::POS_12 => [Position::CENTRE, $positionB],
                Position::POS_13 => [Position::CENTRE, $positionB],
                Position::POS_14 => [Position::WING, $positionB],
                Position::POS_15 => [Position::FULL_BACK, $positionB],
            ];

            foreach ($data as $key => $playerId) {
                $player = Player::find()
                    ->where(['id' => $playerId])
                    ->limit(1)
                    ->one();
                if ($player) {
                    $coefficient = 0;
                    $positionArray = [];
                    foreach ($player->playerPositions as $playerPosition) {
                        $positionArray[] = (int)$playerPosition->position_id;
                    }

                    if (in_array($positionCoefficients[$key][0], $positionArray, true)) {
                        $coefficient = 1;
                    } else {
                        $coefficient = 0.8;
                        foreach ($positionArray as $positionId) {
                            if (in_array($positionId, $positionCoefficients[$key][1], true)) {
                                $coefficient = 0.9;
                            }
                        }
                    }

                    $power += round($player->power_real * $coefficient);
                    $position += round($player->power_real);
                }
            }

            $teamwork = 0;

            $games = Game::find()
                ->joinWith(['schedule'])
                ->andWhere(['not', ['played' => null]])
                ->andWhere(['season_id' => $game->schedule->season_id])
                ->andWhere(['!=', 'tournament_type_id', TournamentType::FRIENDLY])
                ->andWhere([
                    'or',
                    ['home_national_id' => $this->myNationalOrVice->id],
                    ['guest_national_id' => $this->myNationalOrVice->id]
                ])
                ->orderBy(['date' => SORT_DESC])
                ->limit(25)
                ->each();
            foreach ($games as $game) {
                /**
                 * @var Game $game
                 */
                $count = Lineup::find()
                    ->andWhere(['game_id' => $game->id, 'player_id' => $data])
                    ->andWhere(['national_id' => $this->myNationalOrVice->id])
                    ->count();
                if ($count <= 5) {
                    break;
                }
                $teamwork = $teamwork + 0.1 * ($count - 5);
            }

            if (0 === $power) {
                $power = 1;
            }

            $position = $position ? $position : 1;

            $position = round($power / $position * 100);

            $lineup = round($power / $this->myNationalOrVice->power_vs * 100);

            $result = [
                'power' => $power,
                'position' => $position,
                'lineup' => $lineup,
                'teamwork' => $teamwork,
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return GameNationalSend::class;
    }

    /**
     * @param Player $a
     * @param Player $b
     * @return int
     */
    public function sortLineup(Player $a, Player $b): int
    {
        return $a->power_real > $b->power_real ? -1 : 1;
    }
}
