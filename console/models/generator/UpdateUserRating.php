<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Mood;
use common\models\db\Schedule;
use common\models\db\UserRating;
use Exception;

/**
 * Class UpdateUserRating
 * @package console\models\generator
 */
class UpdateUserRating
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $gameArray = Game::find()
            ->with(['homeTeam', 'guestTeam', 'schedule'])
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            /**
             * @var Game $game
             */
            if (!$game->homeTeam->user_id && !$game->guestTeam->user_id) {
                continue;
            }

            $guestAuto = 0;
            $guestCollisionLoose = 0;
            $guestCollisionWin = 0;
            $guestDraw = 0;
            $guestDrawEqual = 0;
            $guestDrawStrong = 0;
            $guestDrawWeak = 0;
            $guestLoose = 0;
            $guestLooseEqual = 0;
            $guestLooseStrong = 0;
            $guestLooseSuper = 0;
            $guestLooseWeak = 0;
            $guestVsSuper = 0;
            $guestVsRest = 0;
            $guestWin = 0;
            $guestWinEqual = 0;
            $guestWinStrong = 0;
            $guestWinSuper = 0;
            $guestWinWeak = 0;
            $homeAuto = 0;
            $homeCollisionLoose = 0;
            $homeCollisionWin = 0;
            $homeDraw = 0;
            $homeDrawEqual = 0;
            $homeDrawStrong = 0;
            $homeDrawWeak = 0;
            $homeLoose = 0;
            $homeLooseEqual = 0;
            $homeLooseStrong = 0;
            $homeLooseSuper = 0;
            $homeLooseWeak = 0;
            $homeVsSuper = 0;
            $homeVsRest = 0;
            $homeWin = 0;
            $homeWinEqual = 0;
            $homeWinStrong = 0;
            $homeWinSuper = 0;
            $homeWinWeak = 0;

            if ($game->guest_auto) {
                $guestAuto++;
            }

            if ($game->home_auto) {
                $homeAuto++;
            }

            if (1 === $game->guest_collision) {
                $guestCollisionWin++;
                $homeCollisionLoose++;
            } elseif (1 === $game->home_collision) {
                $guestCollisionLoose++;
                $homeCollisionWin++;
            }

            if ($game->guest_point > $game->home_point) {
                $guestWin++;
                $homeLoose++;

                if ($game->guest_forecast / $game->home_forecast < 0.9) {
                    $guestWinStrong++;
                    $homeLooseWeak++;
                } elseif ($game->guest_forecast / $game->home_forecast > 1.1) {
                    $guestWinWeak++;
                    $homeLooseStrong++;
                } else {
                    $guestWinEqual++;
                    $homeLooseEqual++;
                }

                if (Mood::SUPER === $game->home_mood_id && Mood::SUPER !== $game->guest_mood_id) {
                    $guestWinSuper++;
                    $homeLooseSuper++;
                }
            } elseif ($game->guest_point === $game->home_point) {
                $guestDraw++;
                $homeDraw++;

                if ($game->guest_forecast / $game->home_forecast < 0.9) {
                    $guestDrawStrong++;
                    $homeDrawWeak++;
                } elseif ($game->guest_forecast / $game->home_forecast > 1.1) {
                    $guestDrawWeak++;
                    $homeDrawStrong++;
                } else {
                    $guestDrawEqual++;
                    $homeDrawEqual++;
                }
            } elseif ($game->guest_point < $game->home_point) {
                $guestLoose++;
                $homeWin++;

                if ($game->guest_forecast / $game->home_forecast < 0.9) {
                    $guestLooseStrong++;
                    $homeWinWeak++;
                } elseif ($game->guest_forecast / $game->home_forecast > 1.1) {
                    $guestLooseWeak++;
                    $homeWinStrong++;
                } else {
                    $guestLooseEqual++;
                    $homeWinEqual++;
                }

                if (Mood::SUPER !== $game->home_mood_id && Mood::SUPER === $game->guest_mood_id) {
                    $guestLooseSuper++;
                    $homeWinSuper++;
                }
            }

            if (Mood::REST === $game->guest_mood_id && Mood::REST !== $game->home_mood_id) {
                $homeVsRest++;
            } elseif (Mood::REST === $game->home_mood_id && Mood::REST !== $game->guest_mood_id) {
                $guestVsRest++;
            }

            if (Mood::SUPER === $game->guest_mood_id && Mood::SUPER !== $game->home_mood_id) {
                $homeVsSuper++;
            } elseif (Mood::SUPER === $game->home_mood_id && Mood::SUPER !== $game->guest_mood_id) {
                $guestVsSuper++;
            }

            if ($game->guestTeam->user_id) {
                $model = UserRating::find()
                    ->where([
                        'user_id' => $game->guestTeam->user_id,
                        'season_id' => $game->schedule->season_id,
                    ])
                    ->limit(1)
                    ->one();

                if ($model) {
                    $model->auto += $guestAuto;
                    $model->collision_loose += $guestCollisionLoose;
                    $model->collision_win += $guestCollisionWin;
                    $model->draw += $guestDraw;
                    $model->draw_equal += $guestDrawEqual;
                    $model->draw_strong += $guestDrawStrong;
                    $model->draw_weak += $guestDrawWeak;
                    $model->game++;
                    $model->loose += $guestLoose;
                    $model->loose_equal += $guestLooseEqual;
                    $model->loose_strong += $guestLooseStrong;
                    $model->loose_super += $guestLooseSuper;
                    $model->loose_weak += $guestLooseWeak;
                    $model->vs_super += $guestVsSuper;
                    $model->vs_rest += $guestVsRest;
                    $model->win += $guestWin;
                    $model->win_equal += $guestWinEqual;
                    $model->win_strong += $guestWinStrong;
                    $model->win_super += $guestWinSuper;
                    $model->win_weak += $guestWinWeak;
                    $model->save();
                }
            }

            if ($game->homeTeam->user_id) {
                $model = UserRating::find()->where([
                    'user_id' => $game->homeTeam->user_id,
                    'season_id' => $game->schedule->season_id,
                ])->limit(1)->one();

                if ($model) {
                    $model->auto += $homeAuto;
                    $model->collision_loose += $homeCollisionLoose;
                    $model->collision_win += $homeCollisionWin;
                    $model->draw += $homeDraw;
                    $model->draw_equal += $homeDrawEqual;
                    $model->draw_strong += $homeDrawStrong;
                    $model->draw_weak += $homeDrawWeak;
                    $model->game++;
                    $model->loose += $homeLoose;
                    $model->loose_equal += $homeLooseEqual;
                    $model->loose_strong += $homeLooseStrong;
                    $model->loose_super += $homeLooseSuper;
                    $model->loose_weak += $homeLooseWeak;
                    $model->vs_super += $homeVsSuper;
                    $model->vs_rest += $homeVsRest;
                    $model->win += $homeWin;
                    $model->win_equal += $homeWinEqual;
                    $model->win_strong += $homeWinStrong;
                    $model->win_super += $homeWinSuper;
                    $model->win_weak += $homeWinWeak;
                    $model->save();
                }
            }
        }
    }
}
