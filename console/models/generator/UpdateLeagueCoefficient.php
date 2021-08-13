<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\LeagueCoefficient;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\TournamentType;
use Exception;
use yii\db\Expression;

/**
 * Class UpdateLeagueCoefficient
 * @package console\models\generator
 */
class UpdateLeagueCoefficient
{
    /**
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
                    ->andWhere(['tournament_type_id' => TournamentType::LEAGUE])
            ])
            ->orderBy(['game.id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            /**
             * @var Game $game
             */
            $guestLoose = 0;
            $guestDraw = 0;
            $guestWin = 0;
            $homeLoose = 0;
            $homeDraw = 0;
            $homeWin = 0;

            if ($game->home_point > $game->guest_point) {
                $homeWin++;
                $guestLoose++;
            } elseif ($game->guest_point > $game->home_point) {
                $guestWin++;
                $homeLoose++;
            } elseif ($game->guest_point === $game->home_point) {
                $guestDraw++;
                $homeDraw++;
            }

            $model = LeagueCoefficient::find()
                ->where([
                    'team_id' => $game->home_team_id,
                    'season_id' => $game->schedule->season_id,
                ])
                ->limit(1)
                ->one();
            $model->loose += $homeLoose;
            $model->draw += $homeDraw;
            $model->win += $homeWin;
            $model->save();

            $model = LeagueCoefficient::find()
                ->where([
                    'team_id' => $game->guest_team_id,
                    'season_id' => $game->schedule->season_id,
                ])
                ->limit(1)
                ->one();
            $model->loose += $guestLoose;
            $model->draw += $guestDraw;
            $model->win += $guestWin;
            $model->save();
        }

        $expression = new Expression('win * 4 + draw * 2');
        LeagueCoefficient::updateAll(
            ['point' => $expression],
            ['season_id' => Season::getCurrentSeason()]
        );
    }
}
