<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Schedule;

/**
 * Class SetTicketPrice
 * @package console\models\generator
 */
class SetTicketPrice
{
    /**
     * @return void
     */
    public function execute()
    {
        Game::updateAll(
            ['ticket_price' => Game::TICKET_PRICE_DEFAULT],
            [
                'played' => null,
                'ticket_price' => 0,
                'schedule_id' => Schedule::find()
                    ->select(['id'])
                    ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ]
        );
    }
}