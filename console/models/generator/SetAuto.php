<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Mood;
use common\models\db\Rudeness;
use common\models\db\Schedule;
use common\models\db\Style;
use common\models\db\Tactic;
use yii\db\Exception;

/**
 * Class SetAuto
 * @package console\models\generator
 */
class SetAuto
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $gameArray = Game::find()
            ->andWhere(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->andWhere(['or', ['guest_mood_id' => null], ['home_mood_id' => null]])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            /**
             * @var Game $game
             */
            if (!$game->home_mood_id) {
                $game->home_auto = true;
                $game->home_mood_id = Mood::NORMAL;
                $game->home_rudeness_id = Rudeness::NORMAL;
                $game->home_style_id = Style::NORMAL;
                $game->home_tactic_id = Tactic::NORMAL;
            }

            if (!$game->guest_mood_id) {
                $game->guest_auto = true;
                $game->guest_mood_id = Mood::NORMAL;
                $game->guest_rudeness_id = Rudeness::NORMAL;
                $game->guest_style_id = Style::NORMAL;
                $game->guest_tactic_id = Tactic::NORMAL;
            }

            $game->save(true, [
                'home_auto',
                'home_mood_id',
                'home_rudeness_id',
                'home_style_id',
                'home_tactic_id',
                'guest_auto',
                'guest_mood_id',
                'guest_rudeness_id',
                'guest_style_id',
                'guest_tactic_id',
            ]);
        }
    }
}
