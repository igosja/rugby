<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Championship;
use common\models\db\Conference;
use common\models\db\Game;
use common\models\db\League;
use common\models\db\OffSeason;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\Stage;
use common\models\db\TournamentType;
use common\models\db\WorldCup;
use Exception;
use yii\db\Expression;

/**
 * Class Standing
 * @package console\models\generator
 */
class Standing
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
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            /**
             * @var Game $game
             */
            $guestBonusLoose = 0;
            $guestBonusTry = 0;
            $guestLoose = 0;
            $guestDraw = 0;
            $guestWin = 0;
            $homeBonusLoose = 0;
            $homeBonusTry = 0;
            $homeLoose = 0;
            $homeDraw = 0;
            $homeWin = 0;

            if ($game->home_point > $game->guest_point) {
                $homeWin++;
                $guestLoose++;

                if ($game->home_point - $game->guest_point <= 7) {
                    $guestBonusLoose++;
                }
            } elseif ($game->guest_point > $game->home_point) {
                $homeLoose++;
                $guestWin++;

                if ($game->guest_point - $game->home_point <= 7) {
                    $homeBonusLoose++;
                }
            } elseif ($game->home_point === $game->guest_point) {
                $homeDraw++;
                $guestDraw++;
            }

            if ($game->home_try >= 4) {
                $homeBonusTry++;
            }
            if ($game->guest_try >= 4) {
                $guestBonusTry++;
            }

            if (TournamentType::CONFERENCE === $game->schedule->tournament_type_id) {
                $model = Conference::find()->where([
                    'team_id' => $game->home_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $homeBonusLoose;
                    $model->bonus_try += $homeBonusTry;
                    $model->draw += $homeDraw;
                    $model->game++;
                    $model->home++;
                    $model->loose += $homeLoose;
                    $model->point_against += $game->guest_point;
                    $model->point_for += $game->home_point;
                    $model->win += $homeWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'home',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }

                $model = Conference::find()->where([
                    'team_id' => $game->guest_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $guestBonusLoose;
                    $model->bonus_try += $guestBonusTry;
                    $model->draw += $guestDraw;
                    $model->game++;
                    $model->guest++;
                    $model->loose += $guestLoose;
                    $model->point_against += $game->home_point;
                    $model->point_for += $game->guest_point;
                    $model->win += $guestWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'guest',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }
            } elseif (TournamentType::OFF_SEASON === $game->schedule->tournament_type_id) {
                $model = OffSeason::find()->where([
                    'team_id' => $game->home_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $homeBonusLoose;
                    $model->bonus_try += $homeBonusTry;
                    $model->draw += $homeDraw;
                    $model->game++;
                    $model->home++;
                    $model->loose += $homeLoose;
                    $model->point_against += $game->guest_point;
                    $model->point_for += $game->home_point;
                    $model->win += $homeWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'home',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }

                $model = OffSeason::find()->where([
                    'team_id' => $game->guest_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $guestBonusLoose;
                    $model->bonus_try += $guestBonusTry;
                    $model->draw += $guestDraw;
                    $model->game++;
                    $model->guest++;
                    $model->loose += $guestLoose;
                    $model->point_against += $game->home_point;
                    $model->point_for += $game->guest_point;
                    $model->win += $guestWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'guest',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }
            } elseif (TournamentType::CHAMPIONSHIP === $game->schedule->tournament_type_id) {
                $model = Championship::find()->where([
                    'team_id' => $game->home_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $homeBonusLoose;
                    $model->bonus_try += $homeBonusTry;
                    $model->draw += $homeDraw;
                    $model->game++;
                    $model->loose += $homeLoose;
                    $model->point_against += $game->guest_point;
                    $model->point_for += $game->home_point;
                    $model->win += $homeWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }

                $model = Championship::find()->where([
                    'team_id' => $game->guest_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $guestBonusLoose;
                    $model->bonus_try += $guestBonusTry;
                    $model->draw += $guestDraw;
                    $model->game++;
                    $model->loose += $guestLoose;
                    $model->point_against += $game->home_point;
                    $model->point_for += $game->guest_point;
                    $model->win += $guestWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }
            } elseif (TournamentType::LEAGUE === $game->schedule->tournament_type_id &&
                $game->schedule->stage_id >= Stage::TOUR_LEAGUE_1 &&
                $game->schedule->stage_id <= Stage::TOUR_LEAGUE_6) {
                $model = League::find()->where([
                    'team_id' => $game->home_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $homeBonusLoose;
                    $model->bonus_try += $homeBonusTry;
                    $model->draw += $homeDraw;
                    $model->game++;
                    $model->loose += $homeLoose;
                    $model->point_against += $game->guest_point;
                    $model->point_for += $game->home_point;
                    $model->win += $homeWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }

                $model = League::find()->where([
                    'team_id' => $game->guest_team_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $guestBonusLoose;
                    $model->bonus_try += $guestBonusTry;
                    $model->draw += $guestDraw;
                    $model->game++;
                    $model->loose += $guestLoose;
                    $model->point_against += $game->home_point;
                    $model->point_for += $game->guest_point;
                    $model->win += $guestWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }
            } elseif (TournamentType::NATIONAL === $game->schedule->tournament_type_id) {
                $model = WorldCup::find()->where([
                    'national_id' => $game->home_national_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $homeBonusLoose;
                    $model->bonus_try += $homeBonusTry;
                    $model->draw += $homeDraw;
                    $model->game++;
                    $model->loose += $homeLoose;
                    $model->point_against += $game->guest_point;
                    $model->point_for += $game->home_point;
                    $model->win += $homeWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }

                $model = WorldCup::find()->where([
                    'national_id' => $game->guest_national_id,
                    'season_id' => $game->schedule->season_id,
                ])->one();
                if ($model) {
                    $model->bonus_loose += $guestBonusLoose;
                    $model->bonus_try += $guestBonusTry;
                    $model->draw += $guestDraw;
                    $model->game++;
                    $model->loose += $guestLoose;
                    $model->point_against += $game->home_point;
                    $model->point_for += $game->guest_point;
                    $model->win += $guestWin;
                    $model->save(true, [
                        'bonus_loose',
                        'bonus_try',
                        'draw',
                        'game',
                        'loose',
                        'point_against',
                        'point_for',
                        'win',
                    ]);
                }
            }
        }

        $seasonId = Season::getCurrentSeason();

        WorldCup::updateAll([
            'point' => new Expression('win * 4 + draw * 2 + bonus_loose + bonus_try'),
            'difference' => new Expression('point_for - point_against')
        ], ['season_id' => $seasonId]);

        League::updateAll([
            'point' => new Expression('win * 4 + draw * 2 + bonus_loose + bonus_try'),
            'difference' => new Expression('point_for - point_against')
        ], ['season_id' => $seasonId]);

        Championship::updateAll([
            'point' => new Expression('win * 4 + draw * 2 + bonus_loose + bonus_try'),
            'difference' => new Expression('point_for - point_against')
        ], ['season_id' => $seasonId]);

        Conference::updateAll([
            'point' => new Expression('win * 4 + draw * 2 + bonus_loose + bonus_try'),
            'difference' => new Expression('point_for - point_against')
        ], ['season_id' => $seasonId]);

        OffSeason::updateAll([
            'point' => new Expression('win * 4 + draw * 2 + bonus_loose + bonus_try'),
            'difference' => new Expression('point_for - point_against')
        ], ['season_id' => $seasonId]);
    }
}
