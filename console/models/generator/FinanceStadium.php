<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Season;
use common\models\db\TournamentType;
use Exception;
use Yii;

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
     * @var array $logData
     */
    private $logData;

    /**
     * @var int $seasonId
     */
    private $seasonId;

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $this->seasonId = Season::getCurrentSeason();

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

        Yii::$app->db->createCommand()->batchInsert(
            Finance::tableName(),
            ['finance_text_id', 'national_id', 'team_id', 'value', 'value_after', 'value_before', 'date', 'season_id'],
            $this->logData
        )->execute();
    }

    /**
     * @throws Exception
     */
    private function friendly(): void
    {
        $income = floor($this->income / 2);
        $outcome = floor($this->game->stadium->maintenance / 2);

        $this->logData[] = [
            FinanceText::INCOME_TICKET,
            null,
            $this->game->homeTeam->id,
            $income,
            $this->game->homeTeam->finance + $income,
            $this->game->homeTeam->finance,
            time(),
            $this->seasonId,
        ];

        $this->logData[] = [
            FinanceText::OUTCOME_GAME,
            null,
            $this->game->homeTeam->id,
            -$outcome,
            $this->game->homeTeam->finance + $income - $outcome,
            $this->game->homeTeam->finance + $income,
            time(),
            $this->seasonId,
        ];

        $this->game->homeTeam->finance = $this->game->homeTeam->finance + $income - $outcome;
        $this->game->homeTeam->save(true, ['finance']);

        $this->logData[] = [
            FinanceText::INCOME_TICKET,
            null,
            $this->game->guestTeam->id,
            $income,
            $this->game->guestTeam->finance + $income,
            $this->game->guestTeam->finance,
            time(),
            $this->seasonId,
        ];

        $this->logData[] = [
            FinanceText::OUTCOME_GAME,
            null,
            $this->game->guestTeam->id,
            -$outcome,
            $this->game->guestTeam->finance + $income - $outcome,
            $this->game->guestTeam->finance + $income,
            time(),
            $this->seasonId,
        ];

        $this->game->guestTeam->finance = $this->game->guestTeam->finance + $income - $outcome;
        $this->game->guestTeam->save(true, ['finance']);
    }

    /**
     * @throws Exception
     */
    private function national(): void
    {
        $income = floor($this->income / 3);

        $this->logData[] = [
            FinanceText::INCOME_TICKET,
            $this->game->homeNational->id,
            null,
            $income,
            $this->game->homeNational->finance + $income,
            $this->game->homeNational->finance,
            time(),
            $this->seasonId,
        ];

        $this->game->homeNational->finance += $income;
        $this->game->homeNational->save(true, ['finance']);

        $this->logData[] = [
            FinanceText::INCOME_TICKET,
            $this->game->guestNational->id,
            null,
            $income,
            $this->game->guestNational->finance + $income,
            $this->game->guestNational->finance,
            time(),
            $this->seasonId,
        ];

        $this->game->guestNational->finance += $income;
        $this->game->guestNational->save(true, ['finance']);

        $this->logData[] = [
            FinanceText::INCOME_TICKET,
            null,
            $this->game->stadium->team->id,
            $income,
            $this->game->stadium->team->finance + $income,
            $this->game->stadium->team->finance,
            time(),
            $this->seasonId,
        ];

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

        $this->logData[] = [
            FinanceText::INCOME_TICKET,
            null,
            $this->game->homeTeam->id,
            $income,
            $this->game->homeTeam->finance + $income,
            $this->game->homeTeam->finance,
            time(),
            $this->seasonId,
        ];

        $this->logData[] = [
            FinanceText::OUTCOME_GAME,
            null,
            $this->game->homeTeam->id,
            -$outcome,
            $this->game->homeTeam->finance + $income - $outcome,
            $this->game->homeTeam->finance + $income,
            time(),
            $this->seasonId,
        ];

        $this->game->homeTeam->finance = $this->game->homeTeam->finance + $income - $outcome;
        $this->game->homeTeam->save(true, ['finance']);
    }
}
