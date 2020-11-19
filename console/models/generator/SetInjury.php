<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Lineup;
use common\models\db\Player;
use common\models\db\Schedule;
use common\models\db\TournamentType;
use yii\db\Exception;

/**
 * Class SetInjury
 * @package console\models\generator
 */
class SetInjury
{
    public const MIN_GAMES_FOR_INJURY = 100;

    /**
     * @return bool
     * @throws Exception
     */
    public function execute(): bool
    {
        $game = Game::find()
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                    ->andWhere(['tournament_type_id' => [TournamentType::CONFERENCE, TournamentType::OFF_SEASON]])
            ])
            ->count();
        if ($game < self::MIN_GAMES_FOR_INJURY) {
            return true;
        }

        $gameIdArray = Game::find()
            ->select(['id'])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->andWhere(['played' => null])
            ->column();

        for ($i = 0; $i < 10; $i++) {
            /**
             * @var Lineup $lineup
             */
            $lineup = Lineup::find()
                ->joinWith(['player'])
                ->where(['game_id' => $gameIdArray])
                ->andWhere(['is_injury' => false])
                ->andWhere([
                    'not',
                    [
                        'player.team_id' => Player::find()
                            ->select(['team_id'])
                            ->where(['is_injury' => true])
                    ]
                ])
                ->orderBy(['tire' => SORT_DESC])
                ->addOrderBy('RAND()')
                ->limit(1)
                ->one();
            if (!$lineup) {
                return true;
            }

            $lineup->player->is_injury = true;
            $lineup->player->injury_day = random_int(1, 9);
            $lineup->player->save(true, ['is_injury', 'injury_day']);

            History::log([
                'game_id' => $lineup->game_id,
                'history_text_id' => HistoryText::PLAYER_INJURY,
                'player_id' => $lineup->player_id,
                'value' => $lineup->player->injury_day,
            ]);
        }

        return true;
    }
}
