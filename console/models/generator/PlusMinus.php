<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Lineup;
use common\models\db\Mood;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\TournamentType;
use Exception;
use Yii;
use yii\db\Expression;

/**
 * Class PlusMinus
 * @package console\models\generator
 *
 * @property Game $game
 */
class PlusMinus
{
    /**
     * @var Game $game
     */
    private $game;

    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $insertData = [];
        $seasonId = Season::getCurrentSeason();

        $gameArray = Game::find()
            ->with(['schedule'])
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            $this->game = $game;
            $guestCompetition = $this->competition();
            $guestMood = $this->mood('guest', 'home');
            $guestOptimality1 = $this->optimality1('guest');
            $guestOptimality2 = $this->optimality2('guest');
            $guestPower = $this->power('guest', 'home');
            $homeCompetition = $this->competition();
            $homeMood = $this->mood('home', 'guest');
            $homeOptimality1 = $this->optimality1('home');
            $homeOptimality2 = $this->optimality2('home');
            $homePower = $this->power('home', 'guest');
            [$guestPoints, $homePoints] = $this->points();

            $guestTotal = $guestCompetition + $guestMood + $guestOptimality1 + $guestOptimality2 + $guestPower + $guestPoints;

            if (substr($guestTotal * 10, -1)) {
                $guestTotal = $guestTotal + random_int(0, 1) - 0.5;
            }

            if ($guestTotal > 5) {
                $guestTotal = 5;
            } elseif ($guestTotal < -5) {
                $guestTotal = -5;
            }

            $homeTotal = $homeCompetition + $homeMood + $homeOptimality1 + $homeOptimality2 + $homePower + $homePoints;

            if (substr($homeTotal * 10, -1)) {
                $homeTotal = $homeTotal + random_int(0, 1) - 0.5;
            }

            if ($homeTotal > 5) {
                $homeTotal = 5;
            } elseif ($homeTotal < -5) {
                $homeTotal = -5;
            }

            if (TournamentType::NATIONAL === $this->game->schedule->tournament_type_id) {
                if ($homeTotal < 0) {
                    $homeTotal = 0;
                }

                if ($guestTotal < 0) {
                    $guestTotal = 0;
                }
            }

            $this->game->guest_plus_minus = $guestTotal;
            $this->game->guest_plus_minus_competition = $guestCompetition;
            $this->game->guest_plus_minus_mood = $guestMood;
            $this->game->guest_plus_minus_optimality_1 = $guestOptimality1;
            $this->game->guest_plus_minus_optimality_2 = $guestOptimality2;
            $this->game->guest_plus_minus_power = $guestPower;
            $this->game->guest_plus_minus_score = $guestPoints;
            $this->game->home_plus_minus = $homeTotal;
            $this->game->home_plus_minus_competition = $homeCompetition;
            $this->game->home_plus_minus_mood = $homeMood;
            $this->game->home_plus_minus_optimality_1 = $homeOptimality1;
            $this->game->home_plus_minus_optimality_2 = $homeOptimality2;
            $this->game->home_plus_minus_power = $homePower;
            $this->game->home_plus_minus_score = $homePoints;
            $this->game->save(true, [
                'guest_plus_minus',
                'guest_plus_minus_competition',
                'guest_plus_minus_mood',
                'guest_plus_minus_optimality_1',
                'guest_plus_minus_optimality_2',
                'guest_plus_minus_power',
                'guest_plus_minus_score',
                'home_plus_minus',
                'home_plus_minus_competition',
                'home_plus_minus_mood',
                'home_plus_minus_optimality_1',
                'home_plus_minus_optimality_2',
                'home_plus_minus_power',
                'home_plus_minus_score',
            ]);
        }

        $subQuery = Schedule::find()
            ->select(['id'])
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()');

        Game::updateAll(
            ['guest_plus_minus' => new Expression('FLOOR(`guest_plus_minus`)+ROUND(RAND())')],
            ['and', 'CEIL(`guest_plus_minus`)!=`guest_plus_minus`', ['schedule_id' => $subQuery]]
        );
        Game::updateAll(
            ['home_plus_minus' => new Expression('FLOOR(`home_plus_minus`)+ROUND(RAND())')],
            ['and', 'CEIL(`home_plus_minus`)!=`home_plus_minus`', ['schedule_id' => $subQuery]]
        );

        $gameArray = Game::find()
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            $this->game = $game;

            if ($this->game->home_plus_minus < 0) {
                /**
                 * @var Lineup[] $lineupArray
                 */
                $lineupArray = Lineup::find()
                    ->joinWith(['player'])
                    ->where([
                        'lineup.team_id' => $this->game->home_team_id,
                        'lineup.national_id' => $this->game->home_national_id,
                        'game_id' => $this->game->id,
                    ])
                    ->andWhere(['not', ['player.team_id' => null]])
                    ->orderBy('RAND()')
                    ->limit(-$this->game->home_plus_minus)
                    ->all();
                foreach ($lineupArray as $lineup) {
                    $lineup->player->power_nominal--;
                    $lineup->player->save(true, ['power_nominal']);

                    $lineup->power_change = -1;
                    $lineup->save(true, ['power_change']);

                    $insertData[] = [
                        $this->game->id,
                        HistoryText::PLAYER_GAME_POINT_MINUS,
                        $lineup->player_id,
                        time(),
                        $seasonId,
                    ];
                }
            } elseif ($this->game->home_plus_minus > 0) {
                /**
                 * @var Lineup[] $lineupArray
                 */
                $lineupArray = Lineup::find()
                    ->joinWith(['player'])
                    ->where([
                        'lineup.team_id' => $this->game->home_team_id,
                        'lineup.national_id' => $this->game->home_national_id,
                        'game_id' => $this->game->id,
                    ])
                    ->andWhere(['not', ['player.team_id' => null]])
                    ->orderBy('RAND()')
                    ->limit($this->game->home_plus_minus)
                    ->all();
                foreach ($lineupArray as $lineup) {
                    $lineup->player->power_nominal++;
                    $lineup->player->save(true, ['power_nominal']);

                    $lineup->power_change = 1;
                    $lineup->save(true, ['power_change']);

                    $insertData[] = [
                        $this->game->id,
                        HistoryText::PLAYER_GAME_POINT_PLUS,
                        $lineup->player_id,
                        time(),
                        $seasonId,
                    ];
                }
            }

            if ($this->game->guest_plus_minus < 0) {
                /**
                 * @var Lineup[] $lineupArray
                 */
                $lineupArray = Lineup::find()
                    ->joinWith(['player'])
                    ->where([
                        'lineup.team_id' => $this->game->guest_team_id,
                        'lineup.national_id' => $this->game->guest_national_id,
                        'game_id' => $this->game->id,
                    ])
                    ->andWhere(['not', ['player.team_id' => null]])
                    ->orderBy('RAND()')
                    ->limit(-$this->game->guest_plus_minus)
                    ->all();
                foreach ($lineupArray as $lineup) {
                    $lineup->player->power_nominal--;
                    $lineup->player->save(true, ['power_nominal']);

                    $lineup->power_change = -1;
                    $lineup->save(true, ['power_change']);

                    $insertData[] = [
                        $this->game->id,
                        HistoryText::PLAYER_GAME_POINT_MINUS,
                        $lineup->player_id,
                        time(),
                        $seasonId,
                    ];
                }
            } elseif ($this->game->guest_plus_minus > 0) {
                /**
                 * @var Lineup[] $lineupArray
                 */
                $lineupArray = Lineup::find()
                    ->joinWith(['player'])
                    ->where([
                        'lineup.team_id' => $this->game->guest_team_id,
                        'lineup.national_id' => $this->game->guest_national_id,
                        'game_id' => $this->game->id,
                    ])
                    ->andWhere(['not', ['player.team_id' => null]])
                    ->orderBy('RAND()')
                    ->limit($this->game->guest_plus_minus)
                    ->all();
                foreach ($lineupArray as $lineup) {
                    $lineup->player->power_nominal++;
                    $lineup->player->save(true, ['power_nominal']);

                    $lineup->power_change = 1;
                    $lineup->save(true, ['power_change']);

                    $insertData[] = [
                        $this->game->id,
                        HistoryText::PLAYER_GAME_POINT_PLUS,
                        $lineup->player_id,
                        time(),
                        $seasonId,
                    ];
                }
            }
        }

        Yii::$app->db->createCommand()->batchInsert(
            History::tableName(),
            ['game_id', 'history_text_id', 'player_id', 'date', 'season_id'],
            $insertData
        )->execute();
    }

    /**
     * @return float
     */
    protected function competition(): float
    {
        if (TournamentType::NATIONAL === $this->game->schedule->tournament_type_id) {
            $result = 2.5;
        } elseif (TournamentType::LEAGUE === $this->game->schedule->tournament_type_id) {
            $result = 2.0;
        } else {
            $result = 0.0;
        }

        return $result;
    }

    /**
     * @param string $team
     * @param string $opponent
     * @return float
     */
    protected function mood(string $team, string $opponent)
    {
        $teamMood = $team . '_mood_id';
        $opponentMood = $opponent . '_mood_id';

        $result = 0;

        if (Mood::SUPER === $this->game->$teamMood) {
            $result--;
        } elseif (Mood::REST === $this->game->$teamMood) {
            $result += 0.5;
        }

        if (Mood::SUPER === $this->game->$opponentMood) {
            $result += 0.5;
        } elseif (Mood::REST === $this->game->$opponentMood) {
            $result--;
        }

        return $result;
    }

    /**
     * @param string $team
     * @return float
     */
    protected function optimality1($team)
    {
        $optimality = $team . '_optimality_1';

        if ($this->game->$optimality > 99) {
            $result = 0.5;
        } elseif ($this->game->$optimality > 96) {
            $result = 0;
        } elseif ($this->game->$optimality > 93) {
            $result = -0.5;
        } elseif ($this->game->$optimality > 90) {
            $result = -1;
        } elseif ($this->game->$optimality > 87) {
            $result = -1.5;
        } elseif ($this->game->$optimality > 84) {
            $result = -2;
        } elseif ($this->game->$optimality > 81) {
            $result = -2.5;
        } elseif ($this->game->$optimality > 78) {
            $result = -3;
        } elseif ($this->game->$optimality > 75) {
            $result = -3.5;
        } elseif ($this->game->$optimality > 72) {
            $result = -4;
        } else {
            $result = -4.5;
        }

        return $result;
    }

    /**
     * @param string $team
     * @return float
     */
    protected function optimality2($team)
    {
        $optimality = $team . '_optimality_2';

        if ($this->game->$optimality > 134) {
            $result = 2.5;
        } elseif ($this->game->$optimality > 124) {
            $result = 2;
        } elseif ($this->game->$optimality > 114) {
            $result = 1.5;
        } elseif ($this->game->$optimality > 109) {
            $result = 1;
        } elseif ($this->game->$optimality > 104) {
            $result = 0.5;
        } elseif ($this->game->$optimality > 49) {
            $result = 0;
        } elseif ($this->game->$optimality > 45) {
            $result = -0.5;
        } elseif ($this->game->$optimality > 42) {
            $result = -1;
        } elseif ($this->game->$optimality > 39) {
            $result = -1.5;
        } elseif ($this->game->$optimality > 36) {
            $result = -2;
        } elseif ($this->game->$optimality > 34) {
            $result = -2.5;
        } elseif ($this->game->$optimality > 32) {
            $result = -3;
        } elseif ($this->game->$optimality > 30) {
            $result = -3.5;
        } elseif ($this->game->$optimality > 28) {
            $result = -4;
        } elseif ($this->game->$optimality > 26) {
            $result = -4.5;
        } else {
            $result = -5;
        }

        return $result;
    }

    /**
     * @param string $team
     * @param string $opponent
     * @return float
     */
    private function power($team, $opponent)
    {
        $percent = $team . '_power_percent';
        $teamPoint = $team . '_point';
        $opponentPoint = $opponent . '_point';

        if ($this->game->$percent > 74) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = -3.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -4;
            } else {
                $result = -4.5;
            }
        } elseif ($this->game->$percent > 70) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = -3;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -3.5;
            } else {
                $result = -4;
            }
        } elseif ($this->game->$percent > 67) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = -2.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -3;
            } else {
                $result = -3.5;
            }
        } elseif ($this->game->$percent > 64) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = -2;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -2.5;
            } else {
                $result = -3;
            }
        } elseif ($this->game->$percent > 61) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = -1.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -2;
            } else {
                $result = -2.5;
            }
        } elseif ($this->game->$percent > 58) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = -1;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -1.5;
            } else {
                $result = -2;
            }
        } elseif ($this->game->$percent > 56) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = -0.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -1;
            } else {
                $result = -1.5;
            }
        } elseif ($this->game->$percent > 54) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 0;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = -0.5;
            } else {
                $result = -1;
            }
        } elseif ($this->game->$percent > 52) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 0.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 0;
            } else {
                $result = -0.5;
            }
        } elseif ($this->game->$percent > 50) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 1;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 0.5;
            } else {
                $result = 0;
            }
        } elseif ($this->game->$percent > 48) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 1.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 1;
            } else {
                $result = 0.5;
            }
        } elseif ($this->game->$percent > 46) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 2;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 1.5;
            } else {
                $result = 1;
            }
        } elseif ($this->game->$percent > 44) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 2.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 2;
            } else {
                $result = 1.5;
            }
        } elseif ($this->game->$percent > 42) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 3;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 2.5;
            } else {
                $result = 2;
            }
        } elseif ($this->game->$percent > 40) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 3.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 3;
            } else {
                $result = 2.5;
            }
        } elseif ($this->game->$percent > 37) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 4;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 3.5;
            } else {
                $result = 3;
            }
        } elseif ($this->game->$percent > 34) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 4.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 4;
            } else {
                $result = 3.5;
            }
        } elseif ($this->game->$percent > 31) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 4.5;
            } else {
                $result = 4;
            }
        } elseif ($this->game->$percent > 28) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 5.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 5;
            } else {
                $result = 4.5;
            }
        } elseif ($this->game->$percent > 24) {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 6;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 5.5;
            } else {
                $result = 5;
            }
        } else {
            if ($this->game->$teamPoint > $this->game->$opponentPoint) {
                $result = 6.5;
            } elseif ($this->game->$teamPoint === $this->game->$opponentPoint) {
                $result = 6;
            } else {
                $result = 5.5;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function points(): array
    {
        $guestPoints = 0;
        $homePoints = 0;

        if ($this->game->guest_point - $this->game->home_point > 64) {
            $guestPoints = 4.5;
            $homePoints = -3.5;
        } elseif ($this->game->guest_point - $this->game->home_point > 56) {
            $guestPoints = 4;
            $homePoints = -3;
        } elseif ($this->game->guest_point - $this->game->home_point > 48) {
            $guestPoints = 3.5;
            $homePoints = -2.5;
        } elseif ($this->game->guest_point - $this->game->home_point > 40) {
            $guestPoints = 3;
            $homePoints = -2;
        } elseif ($this->game->guest_point - $this->game->home_point > 32) {
            $guestPoints = 2.5;
            $homePoints = -1.5;
        } elseif ($this->game->guest_point - $this->game->home_point > 24) {
            $guestPoints = 2;
            $homePoints = -1;
        } elseif ($this->game->guest_point - $this->game->home_point > 16) {
            $guestPoints = 1.5;
            $homePoints = -0.5;
        } elseif ($this->game->guest_point - $this->game->home_point > 8) {
            $guestPoints = 1;
            $homePoints = 0;
        } elseif ($this->game->guest_point - $this->game->home_point > 0) {
            $guestPoints = 0.5;
            $homePoints = 0;
        }

        if ($this->game->home_point - $this->game->guest_point > 64) {
            $homePoints = 4.5;
            $guestPoints = -3.5;
        } elseif ($this->game->home_point - $this->game->guest_point > 56) {
            $homePoints = 4;
            $guestPoints = -3;
        } elseif ($this->game->home_point - $this->game->guest_point > 48) {
            $homePoints = 3.5;
            $guestPoints = -2.5;
        } elseif ($this->game->home_point - $this->game->guest_point > 40) {
            $homePoints = 3;
            $guestPoints = -2;
        } elseif ($this->game->home_point - $this->game->guest_point > 32) {
            $homePoints = 2.5;
            $guestPoints = -1.5;
        } elseif ($this->game->home_point - $this->game->guest_point > 24) {
            $homePoints = 2;
            $guestPoints = -1;
        } elseif ($this->game->home_point - $this->game->guest_point > 16) {
            $homePoints = 1.5;
            $guestPoints = -0.5;
        } elseif ($this->game->home_point - $this->game->guest_point > 8) {
            $homePoints = 1;
            $guestPoints = 0;
        } elseif ($this->game->home_point - $this->game->guest_point > 0) {
            $homePoints = 0.5;
            $guestPoints = 0;
        }

        return [$guestPoints, $homePoints];
    }
}
