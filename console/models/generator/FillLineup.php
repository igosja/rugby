<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Lineup;
use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\Position;
use common\models\db\Schedule;
use Exception;
use Yii;

/**
 * Class FillLineup
 * @package console\models\generator
 */
class FillLineup
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $gameIdArray = Game::find()
            ->select(['id'])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->column();

        $gameArray = Game::find()
            ->with(['guestNational', 'homeNational'])
            ->andWhere([
                'played' => null,
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            $insertData = [];
            $playerIds = [];
            /**
             * @var Game $game
             */
            for ($i = 0; $i < 2; $i++) {
                if (0 === $i) {
                    $nationalId = $game->guest_national_id;
                    $countryId = isset($game->guestNational) ? $game->guestNational->federation->country_id : null;
                    $teamId = $game->guest_team_id;
                } else {
                    $nationalId = $game->home_national_id;
                    $countryId = isset($game->homeNational) ? $game->homeNational->federation->country_id : null;
                    $teamId = $game->home_team_id;
                }

                /**
                 * @var Lineup[] $lineupArray
                 */
                $lineupArray = Lineup::find()
                    ->select([
                        'id',
                        'game_id',
                        'national_id',
                        'player_id',
                        'position_id',
                        'team_id',
                    ])
                    ->where([
                        'game_id' => $game->id,
                        'national_id' => $nationalId,
                        'team_id' => $teamId,
                    ])
                    ->orderBy(['position_id' => SORT_ASC])
                    ->all();
                $positionArray = [
                    Position::POS_01 => Position::PROP,
                    Position::POS_02 => Position::HOOKER,
                    Position::POS_03 => Position::PROP,
                    Position::POS_04 => Position::LOCK,
                    Position::POS_05 => Position::LOCK,
                    Position::POS_06 => Position::FLANKER,
                    Position::POS_07 => Position::FLANKER,
                    Position::POS_08 => Position::EIGHT,
                    Position::POS_09 => Position::SCRUM_HALF,
                    Position::POS_10 => Position::FLY_HALF,
                    Position::POS_11 => Position::WING,
                    Position::POS_12 => Position::CENTRE,
                    Position::POS_13 => Position::CENTRE,
                    Position::POS_14 => Position::WING,
                    Position::POS_15 => Position::FULL_BACK,
                ];

                foreach ($positionArray as $lineupPositionId => $playerPositionId) {
                    $lineup = null;
                    foreach ($lineupArray as $lineupItem) {
                        if ($lineupItem->position_id === $lineupPositionId) {
                            $lineup = $lineupItem;
                            break;
                        }
                    }

                    if (!$lineup || !$lineup->player_id) {
                        $subQuery = Lineup::find()
                            ->select(['player_id'])
                            ->where(['game_id' => $gameIdArray])
                            ->andWhere(['not', ['player_id' => null]]);

                        $league = Player::find()
                            ->select(['id', 'tire'])
                            ->andWhere([
                                'team_id' => 0,
                                'loan_team_id' => null,
                                'is_injury' => false,
                                'power_nominal' => 15,
                                'school_team_id' => 0,
                            ])
                            ->andWhere([
                                'id' => PlayerPosition::find()
                                    ->select(['player_id'])
                                    ->andWhere(['position_id' => $playerPositionId])
                            ])
                            ->andWhere(['not', ['id' => $subQuery]])
                            ->andFilterWhere(['not', ['id' => $playerIds]])
                            ->andWhere(['<=', 'age', Player::AGE_READY_FOR_PENSION])
                            ->andWhere(['<=', 'tire', Player::TIRE_MAX_FOR_LINEUP])
                            ->andFilterWhere(['country_id' => $countryId])
                            ->limit(1);

                        if ($teamId) {
                            $query = Player::find()
                                ->select(['id', 'tire'])
                                ->andWhere([
                                    'is_injury' => false,
                                ])
                                ->andWhere([
                                    'id' => PlayerPosition::find()
                                        ->select(['player_id'])
                                        ->andWhere(['position_id' => $playerPositionId])
                                ])
                                ->andWhere(['not', ['id' => $subQuery]])
                                ->andFilterWhere(['not', ['id' => $playerIds]])
                                ->andWhere(['<=', 'age', Player::AGE_READY_FOR_PENSION])
                                ->andWhere(['<=', 'tire', Player::TIRE_MAX_FOR_LINEUP])
                                ->andWhere([
                                    'or',
                                    ['team_id' => $teamId, 'loan_team_id' => null],
                                    ['loan_team_id' => $teamId],
                                ]);
                        } else {
                            $query = Player::find()
                                ->select(['id', 'tire'])
                                ->andWhere([
                                    'is_injury' => false,
                                    'national_id' => $nationalId,
                                ])
                                ->andWhere([
                                    'id' => PlayerPosition::find()
                                        ->select(['player_id'])
                                        ->andWhere(['position_id' => $playerPositionId])
                                ])
                                ->andWhere(['not', ['id' => $subQuery]])
                                ->andFilterWhere(['not', ['id' => $playerIds]])
                                ->andWhere(['<=', 'age', Player::AGE_READY_FOR_PENSION])
                                ->andWhere(['<=', 'tire', Player::TIRE_MAX_FOR_LINEUP]);
                        }

                        $players = $query->all();
                        if (!$players) {
                            $players = $league->all();
                        }

                        usort($players, static function (Player $a, Player $b) {
                            return ($a->tire - $b->tire);
                        });

                        /**
                         * @var Player $player
                         */
                        $player = $players[0];

                        if ($lineup) {
                            $lineup->player_id = $player->id;
                            $lineup->save(false, [
                                'player_id',
                            ]);
                        } else {
                            $insertData[] = [$lineupPositionId, $teamId, $nationalId, $game->id, $player->id];
                            $playerIds[] = $player->id;
                        }

                    }
                }
            }

            Yii::$app->db->createCommand()->batchInsert(
                Lineup::tableName(),
                ['position_id', 'team_id', 'national_id', 'game_id', 'player_id'],
                $insertData
            )->execute();

            Yii::$app->controller->stdout('.');
        }
    }
}
