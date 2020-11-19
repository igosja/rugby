<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;
use common\models\db\Schedule;
use common\models\db\Stage;
use common\models\db\TournamentType;
use yii\db\Expression;

/**
 * Class GameRowReset
 * @package console\models\generator
 */
class GameRowReset
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $check = Schedule::find()
            ->where('FROM_UNIXTIME(`date`-86400, "%Y-%m-%d")=CURDATE()')
            ->andWhere([
                'stage_id' => Stage::TOUR_1,
                'tournament_type_id' => TournamentType::CHAMPIONSHIP
            ])
            ->limit(1)
            ->one();
        if (!$check) {
            return;
        }

        Player::updateAll(['game_row' => -1], ['<=', 'player_age', Player::AGE_READY_FOR_PENSION]);
        Player::updateAll(
            ['game_row_old' => new Expression('game_row')],
            [
                'and',
                ['<=', 'age', Player::AGE_READY_FOR_PENSION],
                ['!=', 'game_row_old', new Expression('game_row')],
            ]
        );
    }
}
