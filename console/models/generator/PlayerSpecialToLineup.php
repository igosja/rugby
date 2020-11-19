<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Lineup;
use common\models\db\LineupSpecial;
use common\models\db\PlayerSpecial;
use common\models\db\Schedule;
use Exception;

/**
 * Class PlayerSpecialToLineup
 * @package console\models\generator
 */
class PlayerSpecialToLineup
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
            ->andWhere(['played' => null])
            ->column();
        $lineupArray = Lineup::find()
            ->with(['player.playerSpecials'])
            ->select(['id', 'player_id'])
            ->where(['game_id' => $gameIdArray])
            ->andWhere([
                'player_id' => PlayerSpecial::find()->select(['player_id'])
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($lineupArray as $lineup) {
            /**
             * @var Lineup $lineup
             */
            foreach ($lineup->player->playerSpecials as $playerSpecial) {
                $model = new LineupSpecial();
                $model->lineup_id = $lineup->id;
                $model->level = $playerSpecial->level;
                $model->special_id = $playerSpecial->special_id;
                $model->save();
            }
        }
    }
}
