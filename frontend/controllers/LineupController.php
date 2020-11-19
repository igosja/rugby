<?php

namespace frontend\controllers;

use common\models\db\Game;
use common\models\db\Mood;
use common\models\db\Player;
use common\models\db\Position;
use common\models\db\Rudeness;
use common\models\db\Style;
use common\models\db\Tactic;
use common\models\db\TournamentType;
use Exception;
use frontend\models\forms\GameSend;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class LineupController
 * @package frontend\controllers
 */
class LineupController extends AbstractController
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
        if (!$this->myTeamOrVice) {
            return $this->redirect(['team/view']);
        }

        $game = $this->getGame($id);

        $model = new GameSend(['game' => $game, 'team' => $this->myTeamOrVice]);
        if ($model->saveLineup()) {
            $this->setSuccessFlash('Состав успешно отправлен.');
            return $this->refresh();
        }

        $query = Game::find()
            ->joinWith(['schedule'])
            ->where(['played' => null])
            ->andWhere([
                'or',
                ['guest_team_id' => $this->myTeamOrVice->id],
                ['home_team_id' => $this->myTeamOrVice->id]
            ])
            ->orderBy(['date' => SORT_ASC])
            ->limit(5);
        $gameDataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $query = Player::find()
            ->joinWith(['country', 'playerPositions'])
            ->andWhere([
                'or',
                ['team_id' => $this->myTeamOrVice->id, 'loan_team_id' => null],
                ['loan_team_id' => $this->myTeamOrVice->id]
            ]);
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
            ->where([
                'or',
                ['team_id' => $this->myTeamOrVice->id, 'loan_team_id' => null],
                ['loan_team_id' => $this->myTeamOrVice->id]
            ])
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

            if (TournamentType::FRIENDLY === $game->schedule->tournament_type_id) {
                $player01->power_real = round($player01->power_nominal * $pos01Coefficient * 0.75);
                $player02->power_real = round($player02->power_nominal * $pos02Coefficient * 0.75);
                $player03->power_real = round($player03->power_nominal * $pos03Coefficient * 0.75);
                $player04->power_real = round($player04->power_nominal * $pos04Coefficient * 0.75);
                $player05->power_real = round($player05->power_nominal * $pos05Coefficient * 0.75);
                $player06->power_real = round($player06->power_nominal * $pos06Coefficient * 0.75);
                $player07->power_real = round($player07->power_nominal * $pos07Coefficient * 0.75);
                $player08->power_real = round($player08->power_nominal * $pos08Coefficient * 0.75);
                $player09->power_real = round($player09->power_nominal * $pos09Coefficient * 0.75);
                $player10->power_real = round($player10->power_nominal * $pos10Coefficient * 0.75);
                $player11->power_real = round($player11->power_nominal * $pos11Coefficient * 0.75);
                $player12->power_real = round($player12->power_nominal * $pos12Coefficient * 0.75);
                $player13->power_real = round($player13->power_nominal * $pos13Coefficient * 0.75);
                $player14->power_real = round($player14->power_nominal * $pos14Coefficient * 0.75);
                $player15->power_real = round($player15->power_nominal * $pos15Coefficient * 0.75);
            } else {
                $player01->power_real = round($player01->power_nominal * $pos01Coefficient);
                $player02->power_real = round($player02->power_nominal * $pos02Coefficient);
                $player03->power_real = round($player03->power_nominal * $pos03Coefficient);
                $player04->power_real = round($player04->power_nominal * $pos04Coefficient);
                $player05->power_real = round($player05->power_nominal * $pos05Coefficient);
                $player06->power_real = round($player06->power_nominal * $pos06Coefficient);
                $player07->power_real = round($player07->power_nominal * $pos07Coefficient);
                $player08->power_real = round($player08->power_nominal * $pos08Coefficient);
                $player09->power_real = round($player09->power_nominal * $pos09Coefficient);
                $player10->power_real = round($player10->power_nominal * $pos10Coefficient);
                $player11->power_real = round($player11->power_nominal * $pos11Coefficient);
                $player12->power_real = round($player12->power_nominal * $pos12Coefficient);
                $player13->power_real = round($player13->power_nominal * $pos13Coefficient);
                $player14->power_real = round($player14->power_nominal * $pos14Coefficient);
                $player15->power_real = round($player15->power_nominal * $pos15Coefficient);
            }

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
        } elseif ($this->myTeamOrVice->mood_rest <= 0) {
            $noRest = Mood::REST;
        } elseif ($this->myTeamOrVice->mood_super <= 0) {
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
                $moodArray[$moodId] = $moodName . ' (' . $this->myTeamOrVice->mood_super . ')';
            } elseif (Mood::REST === $moodId) {
                $moodArray[$moodId] = $moodName . ' (' . $this->myTeamOrVice->mood_rest . ')';
            }
        }

        $rudenessArray = ArrayHelper::map(Rudeness::find()->all(), 'id', 'name');
        $styleArray = ArrayHelper::map(Style::find()->all(), 'id', 'name');
        $tacticArray = ArrayHelper::map(Tactic::find()->all(), 'id', 'name');

        $this->setSeoTitle('Отправка состава');
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
            'team' => $this->myTeamOrVice,
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
                ['guest_team_id' => $this->myTeamOrVice->id],
                ['home_team_id' => $this->myTeamOrVice->id]
            ])
            ->limit(1)
            ->one();
        $this->notFound($game);

        return $game;
    }

    /**
     * @param int $id
     * @return int[]
     */
    public function actionTeamwork(int $id)
    {
        $result = [
            'power' => 100,
            'power_line_1' => 10,
            'power_line_2' => 20,
            'power_line_3' => 30,
            'power_line_4' => 40,
            'position' => 1,
            'lineup' => 2,
            'teamwork_1' => 3,
            'teamwork_2' => 4,
            'teamwork_3' => 5,
            'teamwork_4' => 6,
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return GameSend::class;
    }

    /**
     * @return string
     */
    public function actionTemplate(): string
    {
        if (!$this->user->isVip()) {
            return '';
        }
        $lineupTemplateArray = LineupTemplate::find()
            ->where(['lineup_template_team_id' => $this->myTeamOrVice->id])
            ->orderBy(['lineup_template_name' => SORT_ASC])
            ->all();
        return $this->renderPartial('_template_table', [
            'lineupTemplateArray' => $lineupTemplateArray,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionTemplateSave(): void
    {
        if (!$this->user->isVip()) {
            return;
        }
        $model = new GameSend();
        $model->saveLineupTemplate();
    }

    /**
     * @param $id
     */
    public function actionTemplateDelete($id): void
    {
        if (!$this->user->isVip()) {
            return;
        }
        $model = LineupTemplate::find()
            ->where(['lineup_template_id' => $id, 'lineup_template_team_id' => $this->myTeam->id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
    }

    /**
     * @param $id
     * @return array|LineupTemplate|ActiveRecord|null
     */
    public function actionTemplateLoad($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = LineupTemplate::find()
            ->where(['lineup_template_id' => $id, 'lineup_template_team_id' => $this->myTeamOrVice->id])
            ->limit(1)
            ->one();
        if (!$model) {
            return (new LineupTemplate())->attributes;
        }
        return $model->attributes;
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
