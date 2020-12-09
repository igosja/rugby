<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\TournamentType;
use Exception;

/**
 * Class FinanceStadium
 * @package console\models\generator
 *
 * @property Game $game
 * @property int $income
 */
class FinanceStadium
{
    /**
     * @var Game $game
     */
    private $game;

    /**
     * @var int $income
     */
    private $income;

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $gameArray = Game::find()
            ->with(['schedule', 'stadium.team', 'homeNational', 'guestNational', 'homeTeam', 'guestTeam'])
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            $this->game = $game;
            $this->income = $this->game->visitor * $this->game->ticket_price;

            if (TournamentType::FRIENDLY === $this->game->schedule->tournament_type_id) {
                $this->friendly();
            } elseif (TournamentType::NATIONAL === $this->game->schedule->tournament_type_id) {
                $this->national();
            } else {
                $this->defaultGame();
            }
        }
    }

    /**
     * @throws Exception
     */
    private function friendly(): void
    {
        $income = floor($this->income / 2);
        $outcome = floor($this->game->stadium->maintenance / 2);

        Finance::log([
            'finance_text_id' => FinanceText::INCOME_TICKET,
            'team_id' => $this->game->homeTeam->id,
            'value' => $income,
            'value_after' => $this->game->homeTeam->finance + $income,
            'value_before' => $this->game->homeTeam->finance,
        ]);

        Finance::log([
            'finance_text_id' => FinanceText::OUTCOME_GAME,
            'team_id' => $this->game->homeTeam->id,
            'value' => -$outcome,
            'value_after' => $this->game->homeTeam->finance + $income - $outcome,
            'value_before' => $this->game->homeTeam->finance + $income,
        ]);

        $this->game->homeTeam->finance = $this->game->homeTeam->finance + $income - $outcome;
        $this->game->homeTeam->save(true, ['finance']);

        Finance::log([
            'finance_text_id' => FinanceText::INCOME_TICKET,
            'team_id' => $this->game->guestTeam->id,
            'value' => $income,
            'value_after' => $this->game->guestTeam->finance + $income,
            'value_before' => $this->game->guestTeam->finance,
        ]);

        Finance::log([
            'finance_text_id' => FinanceText::OUTCOME_GAME,
            'team_id' => $this->game->guestTeam->id,
            'value' => -$outcome,
            'value_after' => $this->game->guestTeam->finance + $income - $outcome,
            'value_before' => $this->game->guestTeam->finance + $income,
        ]);

        $this->game->guestTeam->finance = $this->game->guestTeam->finance + $income - $outcome;
        $this->game->guestTeam->save(true, ['finance']);
    }

    /**
     * @throws Exception
     */
    private function national(): void
    {
        $income = floor($this->income / 3);

        Finance::log([
            'finance_text_id' => FinanceText::INCOME_TICKET,
            'national_id' => $this->game->homeNational->id,
            'value' => $income,
            'value_after' => $this->game->homeNational->finance + $income,
            'value_before' => $this->game->homeNational->finance,
        ]);

        $this->game->homeNational->finance += $income;
        $this->game->homeNational->save(true, ['finance']);

        Finance::log([
            'finance_text_id' => FinanceText::INCOME_TICKET,
            'national_id' => $this->game->guestNational->id,
            'value' => $income,
            'value_after' => $this->game->guestNational->finance + $income,
            'value_before' => $this->game->guestNational->finance,
        ]);

        $this->game->guestNational->finance += $income;
        $this->game->guestNational->save(true, ['finance']);

        Finance::log([
            'finance_text_id' => FinanceText::INCOME_TICKET,
            'team_id' => $this->game->stadium->team->id,
            'value' => $income,
            'value_after' => $this->game->stadium->team->finance + $income,
            'value_before' => $this->game->stadium->team->finance,
        ]);

        $this->game->stadium->team->finance += $income;
        $this->game->stadium->team->save(true, ['finance']);
    }

    /**
     * @throws Exception
     */
    private function defaultGame(): void
    {
        $income = $this->income;
        $outcome = $this->game->stadium->maintenance;

        Finance::log([
            'finance_text_id' => FinanceText::INCOME_TICKET,
            'team_id' => $this->game->homeTeam->id,
            'value' => $income,
            'value_after' => $this->game->homeTeam->finance + $income,
            'value_before' => $this->game->homeTeam->finance,
        ]);

        Finance::log([
            'finance_text_id' => FinanceText::OUTCOME_GAME,
            'team_id' => $this->game->homeTeam->id,
            'value' => -$outcome,
            'value_after' => $this->game->homeTeam->finance + $income - $outcome,
            'value_before' => $this->game->homeTeam->finance + $income,
        ]);

        $this->game->homeTeam->finance = $this->game->homeTeam->finance + $income - $outcome;
        $this->game->homeTeam->save(true, ['finance']);
    }
}
