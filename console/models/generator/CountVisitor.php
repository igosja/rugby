<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Division;
use common\models\db\Game;
use common\models\db\PlayerSpecial;
use common\models\db\Schedule;
use common\models\db\Special;
use common\models\db\TournamentType;
use Exception;

/**
 * Class CountVisitor
 * @package console\models\generator
 */
class CountVisitor
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $gameArray = Game::find()
            ->with([
                'schedule.tournamentType',
                'schedule.stage',
                'guestNational',
                'homeNational.worldCup',
                'guestTeam',
                'homeTeam.championship',
                'stadium',
            ])
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
            $special = PlayerSpecial::find()
                ->indexBy('special_id')
                ->joinWith(['lineup'])
                ->where(['special_id' => Special::IDOL])
                ->andWhere(['game_id' => $game->id])
                ->sum('level');
            if (TournamentType::NATIONAL === $game->schedule->tournament_type_id) {
                $guestVisitor = $game->guestNational->visitor;
                $homeVisitor = $game->homeNational->visitor;
            } else {
                $guestVisitor = $game->guestTeam->visitor;
                $homeVisitor = $game->homeTeam->visitor;
            }

            $divisionId = Division::D1;
            if (in_array($game->schedule->tournament_type_id, [TournamentType::CHAMPIONSHIP, TournamentType::NATIONAL], true)) {
                if (TournamentType::NATIONAL === $game->schedule->tournament_type_id) {
                    $divisionId = $game->homeNational->worldCup->division_id;
                } else {
                    $divisionId = $game->homeTeam->championship->division_id;
                }
            }

            $visitor = $game->stadium->capacity;
            $visitor *= $game->schedule->tournamentType->visitor;
            $visitor *= $game->schedule->stage->visitor;
            $visitor *= (100 + $special * 5) / 100;
            $visitor *= (100 - ($divisionId - 1));

            $ticket = $game->ticket_price;
            if ($ticket < Game::TICKET_PRICE_MIN) {
                $ticket = Game::TICKET_PRICE_MIN;
            } elseif ($ticket > Game::TICKET_PRICE_MAX) {
                $ticket = Game::TICKET_PRICE_MAX;
            }

            $visitor /= ((($ticket - Game::TICKET_PRICE_BASE) / 10) ** 1.1);

            if (in_array($game->schedule->tournament_type_id, [TournamentType::FRIENDLY, TournamentType::NATIONAL], true)) {
                $visitor = $visitor * ($homeVisitor + $guestVisitor) / 2;
            } else {
                $visitor = $visitor * ($homeVisitor * 2 + $guestVisitor) / 3;
            }

            $visitor = round($visitor / 100000000);

            if ($visitor > $game->stadium->capacity) {
                $visitor = $game->stadium->capacity;
            }

            $game->stadium_capacity = $game->stadium->capacity;
            $game->visitor = $visitor;
            $game->ticket_price = $ticket;
            $game->save(true, ['stadium_capacity', 'visitor', 'ticket_price']);
        }
    }
}
