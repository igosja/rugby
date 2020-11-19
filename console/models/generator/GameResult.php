<?php

namespace console\models\generator;

use common\models\db\Event;
use common\models\db\EventText;
use common\models\db\EventType;
use common\models\db\Game;
use common\models\db\Lineup;
use common\models\db\National;
use common\models\db\PlayerPosition;
use common\models\db\PlayerSpecial;
use common\models\db\Position;
use common\models\db\Schedule;
use common\models\db\Special;
use common\models\db\Stage;
use common\models\db\StatisticPlayer;
use common\models\db\StatisticTeam;
use common\models\db\Style;
use common\models\db\Tactic;
use common\models\db\Team;
use common\models\db\TournamentType;
use Exception;
use Yii;

/**
 * Class GameResult
 * @package console\models\generator
 *
 * @property Game $game
 * @property array $result
 * @property array $positions
 * @property array $positions_forward
 * @property array $positions_back
 */
class GameResult
{
    private const AUTO_PENALTY = 25;
    private const COEFFICIENT_FORWARD_ATTACK = 4;
    private const COEFFICIENT_FORWARD_DEFENCE = 10;
    private const COEFFICIENT_HALF_ATTACK = 7;
    private const COEFFICIENT_HALF_DEFENCE = 6;
    private const COEFFICIENT_BACK_ATTACK = 10;
    private const COEFFICIENT_BACK_DEFENCE = 8;
    private const COEFFICIENT_PENALTY_ATTACK = 4;
    private const COEFFICIENT_PENALTY_DEFENCE = 6;
    private const COEFFICIENT_DROP_ATTACK = 1;
    private const COEFFICIENT_DROP_DEFENCE = 225;
    private const COEFFICIENT_YELLOW_CARD = 200;
    private const COEFFICIENT_RED_CARD = 4000;

    /**
     * @var Game|null
     */
    private ?Game $game = null;

    /**
     * @var array $result
     */
    private array $result = [];

    /**
     * @var array $positions
     */
    private array $positions = [
        Position::POS_01,
        Position::POS_02,
        Position::POS_03,
        Position::POS_04,
        Position::POS_05,
        Position::POS_06,
        Position::POS_07,
        Position::POS_08,
        Position::POS_09,
        Position::POS_10,
        Position::POS_11,
        Position::POS_12,
        Position::POS_13,
        Position::POS_14,
        Position::POS_15,
    ];

    /**
     * @var array $positions_forward
     */
    private array $positions_forward = [
        Position::POS_01,
        Position::POS_02,
        Position::POS_03,
        Position::POS_04,
        Position::POS_05,
        Position::POS_06,
        Position::POS_07,
        Position::POS_08,
    ];

    /**
     * @var array $positions_back
     */
    private array $positions_back = [
        Position::POS_11,
        Position::POS_12,
        Position::POS_13,
        Position::POS_14,
        Position::POS_15,
    ];

    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $gameArray = Game::find()
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->andWhere(['guest_optimality_1' => 0, 'home_optimality_1' => 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            $this->game = $game;

            $this->prepareResult();
            $this->countHomeBonus();
            $this->getPlayerInfo();
            $this->countPlayerBonus();
            $this->getTeamwork();
            $this->collision();
            $this->playerOptimalPower();
            $this->countCaptainBonus();
            $this->playerRealPower();
            $this->teamPower();
            $this->teamPowerForecast();
            $this->optimality();

            for ($this->result['minute'] = 0; $this->result['minute'] < 80; $this->result['minute']++) {
                $this->generateMinute();
            }

            $continue = $this->getContinue();

            if ($continue) {
                for ($this->result['minute'] = 80; $this->result['minute'] < 85; $this->result['minute']++) {
                    $this->generateMinute();
                }
            }

            $this->calculateStatistic();
            $this->generateAdditionalData();
            $this->toDataBase();
            Yii::$app->controller->stdout('.');
        }
    }

    /**
     * @return void
     */
    private function prepareResult(): void
    {
        $teamArray = $this->prepareTeamArray();

        $this->result = [
            'game_info' => [
                'id' => $this->game->id,
                'guest_national_id' => $this->game->guest_national_id,
                'guest_team_id' => $this->game->guest_team_id,
                'home_bonus' => 1,
                'home_national_id' => $this->game->home_national_id,
                'home_team_id' => $this->game->home_team_id,
                'tournament_type_id' => $this->game->schedule->tournament_type_id,
            ],
            'guest' => $teamArray,
            'home' => $teamArray,
            'minute' => 0,
            'player' => 0,
        ];

        $this->result['guest']['team']['auto'] = $this->game->guest_auto;
        $this->result['guest']['team']['mood'] = $this->game->guest_mood_id;
        $this->result['guest']['team']['rudeness'] = $this->game->guest_rudeness_id;
        $this->result['guest']['team']['style'] = $this->game->guest_style_id;
        $this->result['guest']['team']['tactic'] = $this->game->guest_tactic_id;
        $this->result['home']['team']['auto'] = $this->game->home_auto;
        $this->result['home']['team']['mood'] = $this->game->home_mood_id;
        $this->result['home']['team']['rudeness'] = $this->game->home_rudeness_id;
        $this->result['home']['team']['style'] = $this->game->home_style_id;
        $this->result['home']['team']['tactic'] = $this->game->home_tactic_id;
    }

    /**
     * @return array
     */
    private function prepareTeamArray(): array
    {
        return [
            'player' => [
                'field' => $this->prepareFieldPlayerArray(),
                'captain' => [
                    'age' => 0,
                    'bonus' => [
                        'age' => 0,
                        'power' => 0,
                    ],
                    'player_id' => 0,
                    'power' => 0,
                ]
            ],
            'team' => [
                'auto' => 0,
                'carry' => 0,
                'clean_break' => 0,
                'collision' => 0,
                'conversion' => 0,
                'defender_beaten' => 0,
                'draw' => 0,
                'drop_goal' => 0,
                'game' => 1,
                'leader' => 0,
                'loose' => 0,
                'metre_gained' => 0,
                'mood' => 0,
                'optimality_1' => 0,
                'optimality_2' => 0,
                'pass' => 0,
                'penalty_conceded' => 0,
                'penalty_kick' => 0,
                'possession' => 0,
                'power' => [
                    'back' => 0,
                    'forecast' => 0,
                    'forward' => 0,
                    'half' => 0,
                    'optimal' => 0,
                    'percent' => 0,
                    'total' => 0,
                ],
                'red_card' => 0,
                'rudeness' => 0,
                'point' => 0,
                'style' => 0,
                'tackle' => 0,
                'tactic' => 0,
                'teamwork' => 0,
                'try' => 0,
                'turnover_won' => 0,
                'yellow_card' => 0,
                'win' => 0,
            ],
        ];
    }

    /**
     * @return array
     */
    private function prepareFieldPlayerArray(): array
    {
        $result = [];
        foreach ($this->positions as $position) {
            $result[$position] = [
                'age' => 0,
                'bonus' => 0,
                'carry' => 0,
                'clean_break' => 0,
                'defender_beaten' => 0,
                'conversion' => 0,
                'draw' => 0,
                'drop_goal' => 0,
                'game' => 1,
                'lineup_id' => 0,
                'loose' => 0,
                'metre_gained' => 0,
                'pass' => 0,
                'penalty_kick' => 0,
                'player_id' => 0,
                'point' => 0,
                'power_nominal' => 0,
                'power_optimal' => 0,
                'power_real' => 0,
                'red_card' => 0,
                'style' => 0,
                'tackle' => 0,
                'try' => 0,
                'turnover_won' => 0,
                'yellow_card' => 0,
                'win' => 0,
            ];
        }

        return $result;
    }

    /**
     * @return void
     */
    private function countHomeBonus(): void
    {
        if ($this->game->bonus_home) {
            $this->result['game_info']['home_bonus'] = 1 + $this->game->visitor / ($this->game->stadium_capacity ?: 1) / 10;
        }
    }

    /**
     * @return void
     */
    private function getPlayerInfo(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            /**
             * @var Lineup[] $lineupArray
             */
            $lineupArray = Lineup::find()
                ->where([
                    'game_id' => $this->result['game_info']['id'],
                    'national_id' => $this->result['game_info'][$team . '_national_id'],
                    'team_id' => $this->result['game_info'][$team . '_team_id'],
                ])
                ->orderBy(['position_id' => SORT_ASC])
                ->all();

            foreach ($this->positions as $position) {
                $key = $position - 1;
                $this->result[$team]['player']['field'][$position]['age'] = $lineupArray[$key]->player->age;
                $this->result[$team]['player']['field'][$position]['lineup_id'] = $lineupArray[$key]->id;
                $this->result[$team]['player']['field'][$position]['player_id'] = $lineupArray[$key]->player_id;
                $this->result[$team]['player']['field'][$position]['power_nominal'] = $lineupArray[$key]->player->power_nominal;
                $this->result[$team]['player']['field'][$position]['style'] = $lineupArray[$key]->player->style_id;

                if (TournamentType::FRIENDLY === $this->result['game_info']['tournament_type_id']) {
                    $this->result[$team]['player']['field'][$position]['power_optimal'] = round($lineupArray[$key]->player->power_nominal * 0.75);
                } else {
                    $this->result[$team]['player']['field'][$position]['power_optimal'] = $lineupArray[$key]->player->power_real;
                }
            }

            foreach ($lineupArray as $lineup) {
                if ($lineup->is_captain) {
                    $this->result[$team]['player']['captain']['age'] = $lineup->player->age;
                    $this->result[$team]['player']['captain']['player_id'] = $lineup->player_id;
                }
            }
        }
    }

    /**
     * @return void
     */
    private function countPlayerBonus(): void
    {
        // TODO implement
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            foreach ($this->positions as $position) {
                if ($this->result[$team]['team']['style'] === $this->result[$team]['player']['field'][$position]['style']) {
                    $this->result[$team]['player']['field'][$position]['bonus'] += 5;
                }

                $playerId = $this->result[$team]['player']['field'][$position]['player_id'];

                /**
                 * @var PlayerSpecial[] $specialArray
                 */
                $specialArray = PlayerSpecial::find()
                    ->where(['player_id' => $playerId])
                    ->all();

                foreach ($specialArray as $special) {
                    if (Special::POWER === $special->special_id) {
                        if (Style::DOWN_THE_MIDDLE === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 5 * $special->level;
                        } elseif (Style::MAN_15 === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 4 * $special->level;
                        } elseif (Style::CHAMPAGNE === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 3 * $special->level;
                        } else {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 2 * $special->level;
                        }
                    } elseif (Special::PASS === $special->special_id) {
                        if (Style::MAN_15 === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 5 * $special->level;
                        } elseif (Style::CHAMPAGNE === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 4 * $special->level;
                        } elseif (Style::MAN_10 === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 3 * $special->level;
                        } else {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 2 * $special->level;
                        }
                    } elseif (Special::COMBINE === $special->special_id) {
                        if (Style::CHAMPAGNE === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 5 * $special->level;
                        } elseif (Style::MAN_10 === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 4 * $special->level;
                        } elseif (Style::DOWN_THE_MIDDLE === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 3 * $special->level;
                        } else {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 2 * $special->level;
                        }
                    } elseif (Special::SCRUM === $special->special_id) {
                        if (Style::MAN_10 === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 5 * $special->level;
                        } elseif (Style::DOWN_THE_MIDDLE === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 4 * $special->level;
                        } elseif (Style::MAN_15 === $this->result[$team]['team']['style']) {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 3 * $special->level;
                        } else {
                            $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 2 * $special->level;
                        }
                    } elseif (Special::TACKLE === $special->special_id) {
                        $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 5 * $special->level;
                    } elseif (in_array($special->special_id, [Special::SPEED, Special::TACKLE, Special::RUCK, Special::MOUL], true)) {
                        $this->result[$team]['player']['field'][$position]['bonus'] = $this->result[$team]['player']['field'][$position]['bonus'] + 5 * $special->level;
                    } elseif (Special::LEADER === $special->special_id) {
                        $this->result[$team]['team']['leader'] += $special->level;
                    }
                }
            }
        }
    }

    /**
     * @return void
     */
    private function getTeamwork(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            $playerIds = [];
            foreach ($this->result[$team]['player']['field'] as $player) {
                $playerIds[] = $player['player_id'];
            }
            $teamwork = 0;

            $games = Game::find()
                ->joinWith(['schedule'])
                ->andWhere(['not', ['played' => null]])
                ->andWhere(['season_id' => $this->game->schedule->season_id])
                ->andWhere(['!=', 'tournament_type_id', TournamentType::FRIENDLY])
                ->andFilterWhere([
                    'or',
                    ['home_team_id' => $this->result['game_info'][$team . '_team_id']],
                    ['guest_team_id' => $this->result['game_info'][$team . '_team_id']]
                ])
                ->andFilterWhere([
                    'or',
                    ['home_national_id' => $this->result['game_info'][$team . '_national_id']],
                    ['guest_national_id' => $this->result['game_info'][$team . '_national_id']]
                ])
                ->orderBy(['date' => SORT_DESC])
                ->limit(25)
                ->each();
            foreach ($games as $game) {
                /**
                 * @var Game $game
                 */
                $count = Lineup::find()
                    ->andWhere(['game_id' => $game->id, 'player_id' => $playerIds])
                    ->andFilterWhere(['team_id' => $this->result['game_info'][$team . '_team_id']])
                    ->andFilterWhere(['national_id' => $this->result['game_info'][$team . '_national_id']])
                    ->andWhere(['position_id' => $this->positions])
                    ->count();
                if ($count <= 5) {
                    break;
                }
                $teamwork = $teamwork + 0.1 * ($count - 5);
            }
            $this->result[$team]['team']['teamwork'] = $teamwork;
        }
    }

    /**
     * @return void
     */
    private function collision(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
                $opponent = 'guest';
            } else {
                $team = 'guest';
                $opponent = 'home';
            }

            if ((Style::MAN_10 === $this->result[$team]['team']['style'] && Style::MAN_15 === $this->result[$opponent]['team']['style']) ||
                (Style::DOWN_THE_MIDDLE === $this->result[$team]['team']['style'] && Style::MAN_10 === $this->result[$opponent]['team']['style']) ||
                (Style::CHAMPAGNE === $this->result[$team]['team']['style'] && Style::DOWN_THE_MIDDLE === $this->result[$opponent]['team']['style']) ||
                (Style::MAN_15 === $this->result[$team]['team']['style'] && Style::CHAMPAGNE === $this->result[$opponent]['team']['style'])) {
                $this->result[$team]['team']['collision'] = 1;
                $this->result[$team]['opponent']['collision'] = -1;
            } elseif ((Style::MAN_15 === $this->result[$team]['team']['style'] && Style::MAN_10 === $this->result[$opponent]['team']['style']) ||
                (Style::MAN_10 === $this->result[$team]['team']['style'] && Style::DOWN_THE_MIDDLE === $this->result[$opponent]['team']['style']) ||
                (Style::DOWN_THE_MIDDLE === $this->result[$team]['team']['style'] && Style::CHAMPAGNE === $this->result[$opponent]['team']['style']) ||
                (Style::CHAMPAGNE === $this->result[$team]['team']['style'] && Style::MAN_15 === $this->result[$opponent]['team']['style'])) {
                $this->result[$team]['team']['collision'] = -1;
                $this->result[$team]['opponent']['collision'] = 1;
            }
        }
    }

    /**
     * @return void
     */
    private function playerOptimalPower(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            foreach ($this->positions as $position) {
                $tactic = 0;
                if (in_array($position, $this->positions_back, true)) {
                    if (Tactic::ALL_ATTACK === $this->result[$team]['team']['tactic']) {
                        $tactic = -10 / 2;
                    } elseif (Tactic::ATTACK === $this->result[$team]['team']['tactic']) {
                        $tactic = -5 / 2;
                    } elseif (Tactic::DEFENCE === $this->result[$team]['team']['tactic']) {
                        $tactic = 5 / 2;
                    } elseif (Tactic::ALL_DEFENCE === $this->result[$team]['team']['tactic']) {
                        $tactic = 10 / 2;
                    } else {
                        $tactic = 0;
                    }
                } elseif (in_array($position, $this->positions_forward, true)) {
                    if (Tactic::ALL_ATTACK === $this->result[$team]['team']['tactic']) {
                        $tactic = 10 / 3;
                    } elseif (Tactic::ATTACK === $this->result[$team]['team']['tactic']) {
                        $tactic = 5 / 3;
                    } elseif (Tactic::DEFENCE === $this->result[$team]['team']['tactic']) {
                        $tactic = -5 / 3;
                    } elseif (Tactic::ALL_DEFENCE === $this->result[$team]['team']['tactic']) {
                        $tactic = -10 / 3;
                    } else {
                        $tactic = 0;
                    }
                }

                if (-1 === $this->result[$team]['team']['collision']) {
                    $collision = 0;
                } else {
                    $collision = $this->result[$team]['team']['collision'];
                }

                $this->result[$team]['player']['field'][$position]['power_optimal'] = round(
                    $this->result[$team]['player']['field'][$position]['power_optimal']
                    * (100 + $this->result[$team]['player']['field'][$position]['bonus'] + $this->result[$team]['team']['leader']) / 100
                    * (100 + $this->result[$team]['team']['teamwork']) / 100
                    * (10 - $this->result[$team]['team']['mood'] + 2) / 10
                    * (100 + $this->result[$team]['team']['rudeness'] - 1) / 100
                    * (10 + $collision) / 10
                    * (100 + $tactic) / 100
                    * (100 - self::AUTO_PENALTY * $this->result[$team]['team']['auto']) / 100
                );

                if ('home' === $team) {
                    $this->result[$team]['player']['field'][$position]['power_optimal'] = round(
                        $this->result[$team]['player']['field'][$position]['power_optimal'] * $this->result['game_info']['home_bonus']
                    );
                }

                if ($this->result[$team]['player']['field'][$position]['player_id'] === $this->result[$team]['player']['captain']['player_id']) {
                    $this->result[$team]['player']['captain']['power'] = $this->result[$team]['player']['field'][$position]['power_optimal'];
                }
            }
        }
    }

    /**
     * @return void
     */
    private function countCaptainBonus(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            if ($this->result[$team]['player']['captain']['age']) {
                if ($this->result[$team]['player']['captain']['age'] >= 38) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = 8;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 36) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = 6;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 34) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = 4;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 32) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = 2;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 30) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = 1;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 27) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = 0;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 25) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = -1;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 23) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = -2;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 21) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = -4;
                } elseif ($this->result[$team]['player']['captain']['age'] >= 19) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = -6;
                } elseif ($this->result[$team]['player']['captain']['age'] < 19) {
                    $this->result[$team]['player']['captain']['bonus']['age'] = -8;
                }
            }

            $teamPower = $this->result[$team]['player']['field'][Position::POS_01]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_02]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_03]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_04]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_05]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_06]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_07]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_08]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_09]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_10]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_11]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_12]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_13]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_14]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_15]['power_optimal'];
            $averagePower = $teamPower / 15;

            if ($this->result[$team]['player']['captain']['power']) {
                if ($this->result[$team]['player']['captain']['power'] / $averagePower > 1.2) {
                    $this->result[$team]['player']['captain']['bonus']['power'] = 2;
                } elseif ($this->result[$team]['player']['captain']['power'] / $averagePower < 0.8) {
                    $this->result[$team]['player']['captain']['bonus']['power'] = -2;
                }
            }

            foreach ($this->positions as $position) {
                $this->result[$team]['player']['field'][$position]['power_optimal'] = $this->useCaptainBonus($this->result[$team]['player']['field'][$position]['power_optimal'], $team);
            }
        }
    }

    /**
     * @param $power
     * @param $team
     * @return float
     */
    private function useCaptainBonus($power, $team): float
    {
        if ($this->result[$team]['player']['captain']['bonus']['age'] > 0) {
            $power = $power + $this->result[$team]['player']['captain']['power'] * $this->result[$team]['player']['captain']['bonus']['age'] / 100;
        } elseif ($this->result[$team]['player']['captain']['bonus']['age'] < 0) {
            $power = $power * (100 - $this->result[$team]['player']['captain']['bonus']['age']) / 100;
        }

        if ($this->result[$team]['player']['captain']['bonus']['power'] > 0) {
            $power = $power + $this->result[$team]['player']['captain']['power'] * $this->result[$team]['player']['captain']['bonus']['power'] / 100;
        } elseif ($this->result[$team]['player']['captain']['bonus']['power'] < 0) {
            $power = $power * (100 - $this->result[$team]['player']['captain']['bonus']['power']) / 100;
        }

        return round($power);
    }

    /**
     * @return void
     */
    private function playerRealPower(): void
    {
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
            Position::POS_08 => [Position::PROP, $positionF],
            Position::POS_09 => [Position::SCRUM_HALF, $positionB],
            Position::POS_10 => [Position::FLY_HALF, $positionB],
            Position::POS_11 => [Position::WING, $positionB],
            Position::POS_12 => [Position::CENTRE, $positionB],
            Position::POS_13 => [Position::CENTRE, $positionB],
            Position::POS_14 => [Position::WING, $positionB],
            Position::POS_15 => [Position::PROP, $positionB],
        ];
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            foreach ($this->positions as $position) {
                $playerId = $this->result[$team]['player']['field'][$position]['player_id'];
                $playerPower = $this->result[$team]['player']['field'][$position]['power_optimal'];

                $positionArray = PlayerPosition::find()
                    ->select(['position_id'])
                    ->where(['player_id' => $playerId])
                    ->column();
                if (in_array($positionCoefficients[$position][0], $positionArray, true)) {
                    $coefficient = 1;
                } else {
                    $coefficient = 0.8;
                    foreach ($positionArray as $positionId) {
                        if (in_array($positionId, $positionCoefficients[$position][1], true)) {
                            $coefficient = 0.9;
                        }
                    }
                }
                $power = round($playerPower * $coefficient);

                $this->result[$team]['player']['field'][$position]['power_real'] = $power;
            }
        }
    }

    /**
     * @return void
     */
    private function teamPower(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            $this->result[$team]['team']['power']['back']
                = $this->result[$team]['player']['field'][Position::POS_11]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_12]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_13]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_14]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_15]['power_real'];
            $this->result[$team]['team']['power']['half']
                = $this->result[$team]['player']['field'][Position::POS_09]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_10]['power_real'];
            $this->result[$team]['team']['power']['forward']
                = $this->result[$team]['player']['field'][Position::POS_01]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_02]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_03]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_04]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_05]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_06]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_07]['power_real']
                + $this->result[$team]['player']['field'][Position::POS_08]['power_real'];
            $this->result[$team]['team']['power']['total']
                = $this->result[$team]['team']['power']['back']
                + $this->result[$team]['team']['power']['half']
                + $this->result[$team]['team']['power']['forward'];
            $this->result[$team]['team']['power']['optimal']
                = $this->result[$team]['player']['field'][Position::POS_01]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_02]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_03]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_04]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_05]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_06]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_07]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_08]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_09]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_10]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_11]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_12]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_13]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_14]['power_optimal']
                + $this->result[$team]['player']['field'][Position::POS_15]['power_optimal'];
        }
    }

    /**
     * @return void
     */
    private function teamPowerForecast(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            $teamId = $this->result['game_info'][$team . '_team_id'];
            $nationalId = $this->result['game_info'][$team . '_national_id'];

            if ($teamId) {
                $power = Team::find()
                    ->select(['power_vs'])
                    ->where(['id' => $teamId])
                    ->limit(1)
                    ->scalar();
            } else {
                $power = National::find()
                    ->select(['power_vs'])
                    ->where(['id' => $nationalId])
                    ->limit(1)
                    ->scalar();
            }

            $this->result[$team]['team']['power']['forecast'] = $power;

            if (!$this->result[$team]['team']['power']['forecast']) {
                $this->result[$team]['team']['power']['forecast'] = $this->result[$team]['team']['power']['optimal'];
            }
        }
    }

    /**
     * @return void
     */
    private function optimality(): void
    {
        $homePowerReal = $this->result['home']['team']['power']['total'];
        $homePowerOptimal = $this->result['home']['team']['power']['optimal'];

        if (0 === $homePowerOptimal) {
            $homePowerOptimal = 1;
        }

        $homeOptimal1 = round($homePowerReal / $homePowerOptimal * 100);

        $guestPowerReal = $this->result['guest']['team']['power']['total'];
        $guestPowerOptimal = $this->result['guest']['team']['power']['optimal'];

        if (!$guestPowerOptimal) {
            $guestPowerOptimal = 1;
        }

        $guestOptimal1 = round($guestPowerReal / $guestPowerOptimal * 100);

        $homeForecast = $this->result['home']['team']['power']['forecast'];

        if (!$homeForecast) {
            $homeForecast = 1;
        }

        $homeOptimal2 = round($homePowerReal / $this->result['game_info']['home_bonus'] / $homeForecast * 100);

        $guestForecast = $this->result['guest']['team']['power']['forecast'];

        if (!$guestForecast) {
            $guestForecast = 1;
        }

        $guestOptimal2 = round($guestPowerReal / $guestForecast * 100);

        if (!$homePowerReal) {
            $homePowerReal = 1;
        }

        if (!$guestPowerReal) {
            $guestPowerReal = 1;
        }

        $teamPowerTotal = $homePowerReal + $guestPowerReal;
        $homePowerPercent = round($homePowerReal / $teamPowerTotal * 100);
        $guestPowerPercent = 100 - $homePowerPercent;

        $this->result['home']['team']['optimality_1'] = $homeOptimal1;
        $this->result['home']['team']['optimality_2'] = $homeOptimal2;
        $this->result['home']['team']['power']['percent'] = $homePowerPercent;
        $this->result['guest']['team']['optimality_1'] = $guestOptimal1;
        $this->result['guest']['team']['optimality_2'] = $guestOptimal2;
        $this->result['guest']['team']['power']['percent'] = $guestPowerPercent;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function generateMinute(): void
    {
        $this->generateCard();
        $this->generateAttack();
    }

    /**
     * @throws Exception
     */
    private function generateCard(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            $rudeness = ($this->result[$team]['team']['rudeness'] - 1) * 10;
            if (random_int(1, self::COEFFICIENT_YELLOW_CARD - $rudeness) <= 1) {
                $this->processYellowCard($team);
            } elseif (random_int(1, self::COEFFICIENT_RED_CARD - $rudeness) <= 1) {
                $this->processRedCard($team);
                $this->result['home']['team']['red_card']++;
            }
        }
    }

    /**
     * @param string $team
     * @throws Exception
     */
    private function processYellowCard(string $team): void
    {
        $this->selectPlayerForCard($team);
        $this->eventYellowCard($team);
        $this->playerYellowIncrease($team);
        $this->teamYellowIncrease($team);
    }

    /**
     * @param string $team
     * @throws Exception
     */
    private function selectPlayerForCard(string $team): void
    {
        $position = random_int(Position::POS_01, Position::POS_07);
        $player = $this->result[$team]['player']['field'][$position];
        if ($player['yellow_card'] || $player['red_card']) {
            $this->selectPlayerForCard($team);
        } else {
            $this->result['player'] = $this->result[$team]['player']['field'][$position]['player_id'];
        }
    }

    /**
     * @param string $team
     */
    private function eventYellowCard(string $team): void
    {
        $this->result['event'][] = array(
            'event_text_id' => EventText::YELLOW_CARD,
            'event_type_id' => EventType::TYPE_CARD,
            'game_id' => $this->result['game_info']['id'],
            'guest_point' => $this->result['guest']['team']['point'],
            'home_point' => $this->result['home']['team']['point'],
            'minute' => $this->result['minute'],
            'national_id' => $this->result['game_info'][$team . '_national_id'],
            'player_id' => $this->result['player'],
            'team_id' => $this->result['game_info'][$team . '_team_id'],
        );
    }

    /**
     * @param string $team
     */
    private function playerYellowIncrease(string $team): void
    {
        foreach ($this->result[$team]['player']['field'] as $position => $player) {
            if ($player['player_id'] === $this->result['player']) {
                $this->result[$team]['player']['field'][$position]['yellow_card']++;
            }
        }
    }

    /**
     * @param string $team
     */
    private function teamYellowIncrease(string $team): void
    {
        $this->result[$team]['team']['yellow_card']++;
    }

    /**
     * @param string $team
     * @throws Exception
     */
    private function processRedCard(string $team): void
    {
        $this->selectPlayerForCard($team);
        $this->eventRedCard($team);
        $this->playerRedIncrease($team);
        $this->teamRedIncrease($team);
    }

    /**
     * @param string $team
     */
    private function eventRedCard(string $team): void
    {
        $this->result['event'][] = array(
            'event_text_id' => EventText::RED_CARD,
            'event_type_id' => EventType::TYPE_CARD,
            'game_id' => $this->result['game_info']['id'],
            'guest_point' => $this->result['guest']['team']['point'],
            'home_point' => $this->result['home']['team']['point'],
            'minute' => $this->result['minute'],
            'national_id' => $this->result['game_info'][$team . '_national_id'],
            'player_id' => $this->result['player'],
            'team_id' => $this->result['game_info'][$team . '_team_id'],
        );
    }

    /**
     * @param string $team
     */
    private function playerRedIncrease(string $team): void
    {
        foreach ($this->result[$team]['player']['field'] as $position => $player) {
            if ($player['player_id'] === $this->result['player']) {
                $this->result[$team]['player']['field'][$position]['red_card']++;
            }
        }
    }

    /**
     * @param string $team
     */
    private function teamRedIncrease(string $team): void
    {
        $this->result[$team]['team']['red_card']++;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function generateAttack(): void
    {
        $this->processAttack();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function processAttack(): void
    {
        if (0 === $this->result['minute'] % 2) {
            $attack = 'home';
            $defence = 'guest';
        } else {
            $attack = 'guest';
            $defence = 'home';
        }

        $backAttack = $this->result[$attack]['team']['power']['back'] * self::COEFFICIENT_BACK_ATTACK;
        $forwardDefence = $this->result[$defence]['team']['power']['forward'] * self::COEFFICIENT_FORWARD_DEFENCE;

        if (random_int(0, $backAttack) > random_int(0, $forwardDefence)) {
            $halfAttack = $this->result[$attack]['team']['power']['half'] * self::COEFFICIENT_HALF_ATTACK;
            $halfDefence = $this->result[$defence]['team']['power']['half'] * self::COEFFICIENT_HALF_DEFENCE;

            if (random_int(0, $halfAttack) > random_int(0, $halfDefence)) {
                $forwardAttack = $this->result[$attack]['team']['power']['forward'] * self::COEFFICIENT_FORWARD_ATTACK;
                $backDefence = $this->result[$defence]['team']['power']['back'] * self::COEFFICIENT_BACK_DEFENCE;
                if (random_int(0, $forwardAttack) > random_int(0, $backDefence)) {
                    $this->selectPlayerForTry($attack);
                    $this->playerTryIncrease($attack);
                    $this->result[$attack]['team']['point'] += 5;
                    $this->result[$attack]['team']['try']++;
                    $this->eventTry($attack);
                    if (random_int(0, 100) > 40) {
                        $this->selectPlayerForKick($attack);
                        $this->playerConversionIncrease($attack);
                        $this->result[$attack]['team']['point'] += 2;
                        $this->result[$attack]['team']['conversion']++;
                        $this->eventConversion($attack);
                    }
                } else {
                    $forwardAttack = $this->result[$attack]['team']['power']['forward'] * self::COEFFICIENT_PENALTY_ATTACK;
                    $backDefence = $this->result[$defence]['team']['power']['back'] * self::COEFFICIENT_PENALTY_DEFENCE;
                    if (random_int(0, $forwardAttack) > random_int(0, $backDefence)) {
                        $this->selectPlayerForKick($attack);
                        $this->playerPenaltyKickIncrease($attack);
                        $this->result[$attack]['team']['point'] += 3;
                        $this->result[$attack]['team']['penalty_kick']++;
                        $this->eventPenaltyKick($attack);
                    } else {
                        $forwardAttack = $this->result[$attack]['team']['power']['forward'] * self::COEFFICIENT_DROP_ATTACK;
                        $backDefence = $this->result[$defence]['team']['power']['back'] * self::COEFFICIENT_DROP_DEFENCE;
                        if (random_int(0, $forwardAttack) > random_int(0, $backDefence)) {
                            $this->selectPlayerForKick($attack);
                            $this->playerDropGoalIncrease($attack);
                            $this->result[$attack]['team']['point'] += 3;
                            $this->result[$attack]['team']['drop_goal']++;
                            $this->eventDropGoal($attack);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $team
     */
    private function playerTryIncrease(string $team): void
    {
        foreach ($this->result[$team]['player']['field'] as $position => $player) {
            if ($player['player_id'] === $this->result['player']) {
                $this->result[$team]['player']['field'][$position]['try']++;
                $this->result[$team]['player']['field'][$position]['point'] += 5;
            }
        }
    }

    /**
     * @param string $team
     */
    private function playerConversionIncrease(string $team): void
    {
        foreach ($this->result[$team]['player']['field'] as $position => $player) {
            if ($player['player_id'] === $this->result['player']) {
                $this->result[$team]['player']['field'][$position]['conversion']++;
                $this->result[$team]['player']['field'][$position]['point'] += 2;
            }
        }
    }

    /**
     * @param string $team
     */
    private function playerPenaltyKickIncrease(string $team): void
    {
        foreach ($this->result[$team]['player']['field'] as $position => $player) {
            if ($player['player_id'] === $this->result['player']) {
                $this->result[$team]['player']['field'][$position]['penalty_kick']++;
                $this->result[$team]['player']['field'][$position]['point'] += 3;
            }
        }
    }

    /**
     * @param string $team
     */
    private function playerDropGoalIncrease(string $team): void
    {
        foreach ($this->result[$team]['player']['field'] as $position => $player) {
            if ($player['player_id'] === $this->result['player']) {
                $this->result[$team]['player']['field'][$position]['drop_goal']++;
                $this->result[$team]['player']['field'][$position]['point'] += 3;
            }
        }
    }

    /**
     * @param string $team
     * @throws Exception
     */
    private function selectPlayerForTry(string $team): void
    {
        $positions = [
            Position::POS_01 => 6,
            Position::POS_02 => 5,
            Position::POS_03 => 6,
            Position::POS_04 => 6,
            Position::POS_05 => 6,
            Position::POS_06 => 6,
            Position::POS_07 => 6,
            Position::POS_08 => 6,
            Position::POS_09 => 3,
            Position::POS_10 => 3,
            Position::POS_11 => 12,
            Position::POS_12 => 11,
            Position::POS_13 => 11,
            Position::POS_14 => 12,
            Position::POS_15 => 1,
        ];

        $positionLimits = [];
        $power = 0;
        foreach ($positions as $position => $coefficient) {
            $power += $coefficient * $this->result[$team]['player']['field'][Position::POS_01]['power_real'];
            $positionLimits[$position] = $power;
        }
        $rand = random_int(0, $power);
        foreach ($positionLimits as $position => $limit) {
            if ($rand <= $limit) {
                $this->result['player'] = $this->result[$team]['player']['field'][$position]['player_id'];
                return;
            }
        }
        $this->result['player'] = $this->result[$team]['player']['field'][Position::POS_14]['player_id'];
    }

    /**
     * @param string $team
     * @throws Exception
     */
    private function selectPlayerForKick(string $team): void
    {
        if (random_int(1, 20) <= 1) {
            $this->result['player'] = $this->result[$team]['player']['field'][Position::POS_15]['player_id'];
            return;
        }
        $this->result['player'] = $this->result[$team]['player']['field'][Position::POS_10]['player_id'];
    }

    /**
     * @param string $team
     */
    private function eventTry(string $team): void
    {
        $this->eventGoal($team, EventText::TRY);
    }

    /**
     * @param string $team
     */
    private function eventConversion(string $team): void
    {
        $this->eventGoal($team, EventText::CONVERSION);
    }

    /**
     * @param string $team
     */
    private function eventPenaltyKick(string $team): void
    {
        $this->eventGoal($team, EventText::PENALTY_KICK);
    }

    /**
     * @param string $team
     */
    private function eventDropGoal(string $team): void
    {
        $this->eventGoal($team, EventText::DROP_GOAL);
    }

    /**
     * @param string $team
     * @param int $eventText
     */
    private function eventGoal(string $team, int $eventText): void
    {
        $this->result['event'][] = array(
            'event_text_id' => $eventText,
            'event_type_id' => EventType::TYPE_GOAL,
            'game_id' => $this->result['game_info']['id'],
            'guest_point' => $this->result['guest']['team']['point'],
            'home_point' => $this->result['home']['team']['point'],
            'minute' => $this->result['minute'],
            'national_id' => $this->result['game_info'][$team . '_national_id'],
            'player_id' => $this->result['player'],
            'team_id' => $this->result['game_info'][$team . '_team_id'],
        );
    }

    /**
     * @return bool
     */
    private function getContinue(): bool
    {
        $result = false;

        $isScoreEquals = ($this->result['home']['team']['point'] === $this->result['guest']['team']['point']);
        $isLeague = (TournamentType::LEAGUE === $this->game->schedule->tournament_type_id);
        $isGroup = in_array($this->game->schedule->stage_id, [
            Stage::TOUR_LEAGUE_1,
            Stage::TOUR_LEAGUE_2,
            Stage::TOUR_LEAGUE_3,
            Stage::TOUR_LEAGUE_4,
            Stage::TOUR_LEAGUE_5,
            Stage::TOUR_LEAGUE_6,
        ], true);
        $isKnockOut = in_array($this->game->schedule->stage_id, [
            Stage::QUALIFY_1,
            Stage::QUALIFY_2,
            Stage::QUALIFY_3,
            Stage::ROUND_OF_16,
            Stage::QUARTER,
            Stage::SEMI,
            Stage::FINAL_GAME,
        ], true);

        if ($isScoreEquals && !$isLeague) {
            $result = false;
        } elseif ($isScoreEquals && $isLeague && $isGroup) {
            $result = false;
        } elseif ($isLeague && $isKnockOut) {
            /**
             * @var Game $prev
             */
            $prev = Game::find()
                ->joinWith(['schedule'])
                ->where([
                    'or',
                    [
                        'guest_team_id' => $this->game->guest_team_id,
                        'home_team_id' => $this->game->home_team_id
                    ],
                    [
                        'home_team_id' => $this->game->guest_team_id,
                        'guest_team_id' => $this->game->home_team_id
                    ],
                ])
                ->andWhere([
                    'season_id' => $this->game->schedule->season_id,
                    'tournament_type_id' => $this->game->schedule->tournament_type_id,
                    'stage_id' => $this->game->schedule->stage_id,
                ])
                ->andWhere(['not', ['played' => null]])
                ->limit(1)
                ->one();

            if ($prev) {
                if ($this->game->home_team_id === $prev->home_team_id) {
                    $homeScoreWithPrev = $this->result['home']['team']['point'] + $prev->home_point;
                    $guestScoreWithPrev = $this->result['guest']['team']['point'] + $prev->guest_point;
                } else {
                    $homeScoreWithPrev = $this->result['home']['team']['point'] + $prev->guest_point;
                    $guestScoreWithPrev = $this->result['guest']['team']['point'] + $prev->home_point;
                }

                if ($homeScoreWithPrev === $guestScoreWithPrev) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function calculateStatistic(): void
    {
        $win = $loose = null;
        if ($this->result['home']['team']['point'] === $this->result['guest']['team']['point']) {
            $this->result['home']['team']['draw'] = 1;
            $this->result['guest']['team']['draw'] = 1;
        } elseif ($this->result['home']['team']['point'] > $this->result['guest']['team']['point']) {
            $this->result['home']['team']['win'] = 1;
            $this->result['guest']['team']['loose'] = 1;

            $win = 'home';
            $loose = 'guest';
        } else {
            $this->result['guest']['team']['win'] = 1;
            $this->result['home']['team']['loose'] = 1;

            $loose = 'home';
            $win = 'guest';
        }

        foreach ($this->positions as $position) {
            if ($win) {
                $this->result[$win]['player']['field'][$position]['win'] = 1;
                $this->result[$loose]['player']['field'][$position]['loose'] = 1;
            } else {
                $this->result['home']['player']['field'][$position]['draw'] = 1;
                $this->result['guest']['player']['field'][$position]['draw'] = 1;
            }
        }

        $this->result['guest']['team']['pass'] = $this->result['home']['team']['point'];
        $this->result['home']['team']['pass'] = $this->result['guest']['team']['point'];
    }

    /**
     * @throws Exception
     */
    private function generateAdditionalData(): void
    {
        $homePower = $this->result['home']['team']['power']['total'];
        $guestPower = $this->result['guest']['team']['power']['total'];

        $homeCoefficient = $homePower / ($homePower + $guestPower);
        $guestCoefficient = $guestPower / ($homePower + $guestPower);

        $this->result['home']['team']['possession'] = abs(round(100 * ($homeCoefficient + random_int(-5, 5) / 100)));
        $this->result['guest']['team']['possession'] = 100 - $this->result['home']['team']['possession'];

        $penalty_conceded = random_int(floor(9.57 * 0.8), ceil(9.57 * 1.2));
        $this->result['home']['team']['penalty_conceded'] = abs(round($penalty_conceded * 2 * ($homeCoefficient + random_int(-5, 5) / 100)));
        $this->result['guest']['team']['penalty_conceded'] = abs($penalty_conceded - $this->result['home']['team']['penalty_conceded']);

        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
                $coefficient = $homeCoefficient;
            } else {
                $team = 'guest';
                $coefficient = $guestCoefficient;
            }

            $limits = [
                'carry' => 129.7,
                'clean_break' => 9.6,
                'defender_beaten' => 23.1,
                'metre_gained' => 407.9,
                'pass' => 157.1,
                'tackle' => 146,
                'turnover_won' => 6,
            ];

            $distribution = [
                'carry' => [
                    Position::POS_01 => 4.22,
                    Position::POS_02 => 6.40,
                    Position::POS_03 => 4.22,
                    Position::POS_04 => 5.98,
                    Position::POS_05 => 5.97,
                    Position::POS_06 => 6.79,
                    Position::POS_07 => 6.80,
                    Position::POS_08 => 10.49,
                    Position::POS_09 => 5.81,
                    Position::POS_10 => 5.48,
                    Position::POS_11 => 6.91,
                    Position::POS_12 => 7.21,
                    Position::POS_13 => 7.21,
                    Position::POS_14 => 6.91,
                    Position::POS_15 => 9.39,
                ],
                'clean_break' => [
                    Position::POS_01 => 0.42,
                    Position::POS_02 => 2.08,
                    Position::POS_03 => 0.42,
                    Position::POS_04 => 2.70,
                    Position::POS_05 => 2.70,
                    Position::POS_06 => 5.62,
                    Position::POS_07 => 5.62,
                    Position::POS_08 => 9.17,
                    Position::POS_09 => 5.41,
                    Position::POS_10 => 5.00,
                    Position::POS_11 => 15.41,
                    Position::POS_12 => 9.58,
                    Position::POS_13 => 9.58,
                    Position::POS_14 => 15.41,
                    Position::POS_15 => 10.84,
                ],
                'defender_beaten' => [
                    Position::POS_01 => 1.81,
                    Position::POS_02 => 3.15,
                    Position::POS_03 => 1.81,
                    Position::POS_04 => 2.84,
                    Position::POS_05 => 2.84,
                    Position::POS_06 => 4.81,
                    Position::POS_07 => 4.81,
                    Position::POS_08 => 12.15,
                    Position::POS_09 => 5.05,
                    Position::POS_10 => 6.31,
                    Position::POS_11 => 12.85,
                    Position::POS_12 => 7.88,
                    Position::POS_13 => 7.88,
                    Position::POS_14 => 12.85,
                    Position::POS_15 => 12.94,
                ],
                'metre_gained' => [
                    Position::POS_01 => 1.13,
                    Position::POS_02 => 2.87,
                    Position::POS_03 => 1.13,
                    Position::POS_04 => 2.91,
                    Position::POS_05 => 2.91,
                    Position::POS_06 => 4.71,
                    Position::POS_07 => 4.71,
                    Position::POS_08 => 8.98,
                    Position::POS_09 => 6.44,
                    Position::POS_10 => 5.50,
                    Position::POS_11 => 12.54,
                    Position::POS_12 => 8.58,
                    Position::POS_13 => 8.58,
                    Position::POS_14 => 12.54,
                    Position::POS_15 => 16.45,
                ],
                'pass' => [
                    Position::POS_01 => 1.43,
                    Position::POS_02 => 1.80,
                    Position::POS_03 => 1.43,
                    Position::POS_04 => 2.09,
                    Position::POS_05 => 2.09,
                    Position::POS_06 => 3.60,
                    Position::POS_07 => 3.60,
                    Position::POS_08 => 4.04,
                    Position::POS_09 => 43.85,
                    Position::POS_10 => 19.10,
                    Position::POS_11 => 2.67,
                    Position::POS_12 => 4.24,
                    Position::POS_13 => 4.24,
                    Position::POS_14 => 2.67,
                    Position::POS_15 => 5.14,
                ],
                'tackle' => [
                    Position::POS_01 => 6.51,
                    Position::POS_02 => 7.89,
                    Position::POS_03 => 6.51,
                    Position::POS_04 => 8.87,
                    Position::POS_05 => 8.87,
                    Position::POS_06 => 10.05,
                    Position::POS_07 => 10.05,
                    Position::POS_08 => 9.68,
                    Position::POS_09 => 4.71,
                    Position::POS_10 => 5.48,
                    Position::POS_11 => 3.35,
                    Position::POS_12 => 6.32,
                    Position::POS_13 => 6.32,
                    Position::POS_14 => 3.35,
                    Position::POS_15 => 2.00,
                ],
                'turnover_won' => [
                    Position::POS_01 => 4.80,
                    Position::POS_02 => 4.52,
                    Position::POS_03 => 5.80,
                    Position::POS_04 => 6.78,
                    Position::POS_05 => 6.78,
                    Position::POS_06 => 14.97,
                    Position::POS_07 => 14.97,
                    Position::POS_08 => 10.74,
                    Position::POS_09 => 4.52,
                    Position::POS_10 => 2.27,
                    Position::POS_11 => 5.65,
                    Position::POS_12 => 4.51,
                    Position::POS_13 => 4.51,
                    Position::POS_14 => 5.65,
                    Position::POS_15 => 4.52,
                ],
            ];
            foreach ($distribution as $indicator => $positions) {
                $base = $limits[$indicator] * 2 * $coefficient;
                $value = random_int(floor($base * 0.8), ceil($base * 1.2));
                $this->result[$team]['team'][$indicator] = $value;

                $players = [];
                $powerLevel = 0;
                foreach ($positions as $position => $percent) {
                    $powerLevel += ($percent * 100 * $this->result[$team]['player']['field'][$position]['power_real']);
                    $players[$position] = $powerLevel;
                }

                for ($j = 0; $j < $value; $j++) {
                    $rand = random_int(0, $powerLevel);
                    foreach ($players as $playerPosition => $power) {
                        if ($rand <= $power) {
                            $this->result[$team]['player']['field'][$playerPosition][$indicator]++;
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function toDataBase(): void
    {
        Event::deleteAll(['game_id' => $this->game->id]);
        $this->gameToDataBase();
        $this->eventToDataBase();
        $this->lineupToDataBase();
        $this->statisticToDataBase();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function gameToDataBase(): void
    {
        $this->game->guest_carry = $this->result['guest']['team']['carry'];
        $this->game->guest_clean_break = $this->result['guest']['team']['clean_break'];
        $this->game->guest_collision = $this->result['guest']['team']['collision'];
        $this->game->guest_conversion = $this->result['guest']['team']['conversion'];
        $this->game->guest_defender_beaten = $this->result['guest']['team']['defender_beaten'];
        $this->game->guest_drop_goal = $this->result['guest']['team']['drop_goal'];
        $this->game->guest_forecast = $this->result['guest']['team']['metre_gained'];
        $this->game->guest_metre_gained = $this->result['guest']['team']['metre_gained'];
        $this->game->guest_optimality_1 = $this->result['guest']['team']['optimality_1'];
        $this->game->guest_optimality_2 = $this->result['guest']['team']['optimality_2'];
        $this->game->guest_pass = $this->result['guest']['team']['pass'];
        $this->game->guest_penalty_conceded = $this->result['guest']['team']['penalty_conceded'];
        $this->game->guest_penalty_kick = $this->result['guest']['team']['penalty_kick'];
        $this->game->guest_point = $this->result['guest']['team']['point'];
        $this->game->guest_possession = $this->result['guest']['team']['possession'];
        $this->game->guest_power = $this->result['guest']['team']['power']['total'];
        $this->game->guest_power_percent = $this->result['guest']['team']['power']['percent'];
        $this->game->guest_red_card = $this->result['guest']['team']['red_card'];
        $this->game->guest_tackle = $this->result['guest']['team']['tackle'];
        $this->game->guest_teamwork = $this->result['guest']['team']['teamwork'];
        $this->game->guest_try = $this->result['guest']['team']['try'];
        $this->game->guest_turnover_won = $this->result['guest']['team']['turnover_won'];
        $this->game->guest_yellow_card = $this->result['guest']['team']['yellow_card'];
        $this->game->home_carry = $this->result['home']['team']['carry'];
        $this->game->home_clean_break = $this->result['home']['team']['clean_break'];
        $this->game->home_collision = $this->result['home']['team']['collision'];
        $this->game->home_conversion = $this->result['home']['team']['conversion'];
        $this->game->home_defender_beaten = $this->result['home']['team']['defender_beaten'];
        $this->game->home_drop_goal = $this->result['home']['team']['drop_goal'];
        $this->game->home_forecast = $this->result['home']['team']['metre_gained'];
        $this->game->home_metre_gained = $this->result['home']['team']['metre_gained'];
        $this->game->home_optimality_1 = $this->result['home']['team']['optimality_1'];
        $this->game->home_optimality_2 = $this->result['home']['team']['optimality_2'];
        $this->game->home_pass = $this->result['home']['team']['pass'];
        $this->game->home_penalty_conceded = $this->result['home']['team']['penalty_conceded'];
        $this->game->home_penalty_kick = $this->result['home']['team']['penalty_kick'];
        $this->game->home_point = $this->result['home']['team']['point'];
        $this->game->home_possession = $this->result['home']['team']['possession'];
        $this->game->home_power = $this->result['home']['team']['power']['total'];
        $this->game->home_power_percent = $this->result['home']['team']['power']['percent'];
        $this->game->home_red_card = $this->result['home']['team']['red_card'];
        $this->game->home_tackle = $this->result['home']['team']['tackle'];
        $this->game->home_teamwork = $this->result['home']['team']['teamwork'];
        $this->game->home_try = $this->result['home']['team']['try'];
        $this->game->home_turnover_won = $this->result['home']['team']['turnover_won'];
        $this->game->home_yellow_card = $this->result['home']['team']['yellow_card'];
        $this->game->save();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function eventToDataBase(): void
    {
        foreach ($this->result['event'] as $event) {
            Event::log($event);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function lineupToDataBase(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            foreach ($this->result[$team]['player']['field'] as $player) {
                if (isset($player['lineup_id']) && $player['lineup_id']) {
                    $model = Lineup::find()
                        ->where(['id' => $player['lineup_id']])
                        ->limit(1)
                        ->one();
                    if ($model) {
                        $model->age = $player['age'];
                        $model->clean_break = $player['clean_break'];
                        $model->conversion = $player['conversion'];
                        $model->defender_beaten = $player['defender_beaten'];
                        $model->drop_goal = $player['drop_goal'];
                        $model->metre_gained = $player['metre_gained'];
                        $model->pass = $player['pass'];
                        $model->penalty_kick = $player['penalty_kick'];
                        $model->point = $player['point'];
                        $model->power_nominal = $player['power_nominal'];
                        $model->power_real = $player['power_real'];
                        $model->red_card = $player['red_card'];
                        $model->tackle = $player['tackle'];
                        $model->try = $player['try'];
                        $model->turnover_won = $player['turnover_won'];
                        $model->yellow_card = $player['yellow_card'];
                        $model->save();
                    }
                }
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function statisticToDataBase(): void
    {
        $federationId = $this->game->homeTeam->championship->federation_id ?? null;
        $divisionId = $this->game->homeTeam->championship->division_id ?? null;

        if (in_array($this->game->schedule->tournament_type_id, [
            TournamentType::FRIENDLY,
            TournamentType::CONFERENCE,
            TournamentType::LEAGUE,
            TournamentType::OFF_SEASON,
        ], true)) {
            $federationId = null;
            $divisionId = null;
        }

        if (TournamentType::NATIONAL === $this->game->schedule->tournament_type_id) {
            $divisionId = $this->game->homeNational->worldCup->division_id ?? null;
        }

        for ($i = 0; $i < 2; $i++) {
            if (0 === $i) {
                $team = 'home';
            } else {
                $team = 'guest';
            }

            foreach ($this->result[$team]['player']['field'] as $player) {
                if (isset($player['player_id']) && $player['player_id']) {
                    $model = StatisticPlayer::find()->where([
                        'federation_id' => $federationId,
                        'division_id' => $divisionId,
                        'national_id' => $this->result['game_info'][$team . '_national_id'],
                        'player_id' => $player['player_id'],
                        'season_id' => $this->game->schedule->season_id,
                        'team_id' => $this->result['game_info'][$team . '_team_id'],
                        'tournament_type_id' => $this->game->schedule->tournament_type_id,
                    ])->limit(1)->one();
                    if ($model) {
                        $model->clean_break += $player['clean_break'];
                        $model->defender_beaten += $player['defender_beaten'];
                        $model->draw += $player['draw'];
                        $model->game += $player['game'];
                        $model->loose += $player['loose'];
                        $model->metre_gained += $player['metre_gained'];
                        $model->pass += $player['pass'];
                        $model->point += $player['point'];
                        $model->red_card += $player['red_card'];
                        $model->tackle += $player['tackle'];
                        $model->try += $player['try'];
                        $model->turnover_won += $player['turnover_won'];
                        $model->win += $player['win'];
                        $model->yellow_card += $player['yellow_card'];
                        $model->save();
                    }
                }
            }

            $model = StatisticTeam::find()->where([
                'federation_id' => $federationId,
                'division_id' => $divisionId,
                'national_id' => $this->result['game_info'][$team . '_national_id'],
                'season_id' => $this->game->schedule->season_id,
                'team_id' => $this->result['game_info'][$team . '_team_id'],
                'tournament_type_id' => $this->game->schedule->tournament_type_id,
            ])->limit(1)->one();
            if ($model) {
                $model->carry += $this->result[$team]['team']['carry'];
                $model->clean_break += $this->result[$team]['team']['clean_break'];
                $model->defender_beaten += $this->result[$team]['team']['defender_beaten'];
                $model->draw += $this->result[$team]['team']['draw'];
                $model->drop_goal += $this->result[$team]['team']['drop_goal'];
                $model->game += $this->result[$team]['team']['game'];
                $model->loose += $this->result[$team]['team']['loose'];
                $model->metre_gained += $this->result[$team]['team']['metre_gained'];
                $model->pass += $this->result[$team]['team']['pass'];
                $model->penalty_conceded += $this->result[$team]['team']['penalty_conceded'];
                $model->point += $this->result[$team]['team']['point'];
                $model->red_card += $this->result[$team]['team']['red_card'];
                $model->tackle += $this->result[$team]['team']['tackle'];
                $model->try += $this->result[$team]['team']['try'];
                $model->turnover_won += $this->result[$team]['team']['turnover_won'];
                $model->win += $this->result[$team]['team']['win'];
                $model->yellow_card += $this->result[$team]['team']['yellow_card'];
                $model->save();
            }
        }
    }
}
