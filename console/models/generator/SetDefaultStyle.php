<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Rudeness;
use common\models\db\Schedule;
use common\models\db\Style;
use common\models\db\Tactic;
use Exception;

/**
 * Class SetDefaultStyle
 * @package console\models\generator
 */
class SetDefaultStyle
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $gameArray = Game::find()
            ->joinWith(['schedule'])
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->andWhere([
                'or',
                ['guest_rudeness_id' => null],
                ['guest_style_id' => null],
                ['guest_tactic_id' => null],
                ['home_rudeness_id' => null],
                ['home_style_id' => null],
                ['home_tactic_id' => null],
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            /**
             * @var Game $game
             */
            if (!$game->home_rudeness_id) {
                $game->home_rudeness_id = Rudeness::NORMAL;
            }
            if (!$game->home_style_id) {
                $game->home_style_id = Style::NORMAL;
            }
            if (!$game->home_tactic_id) {
                $game->home_tactic_id = Tactic::NORMAL;
            }

            if (!$game->guest_rudeness_id) {
                $game->guest_rudeness_id = Rudeness::NORMAL;
            }
            if (!$game->guest_style_id) {
                $game->guest_style_id = Style::NORMAL;
            }
            if (!$game->guest_tactic_id) {
                $game->guest_tactic_id = Tactic::NORMAL;
            }

            $game->save(true, [
                'home_rudeness_id',
                'home_style_id',
                'home_tactic_id',
                'guest_rudeness_id',
                'guest_style_id',
                'guest_tactic_id',
            ]);
        }
    }
}
