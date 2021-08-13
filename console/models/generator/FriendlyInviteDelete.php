<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\FriendlyInvite;
use common\models\db\Schedule;

/**
 * Class FriendlyInviteDelete
 * @package console\models\generator
 */
class FriendlyInviteDelete
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $schedule = Schedule::find()
            ->where('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();

        FriendlyInvite::deleteAll(['<=', 'schedule_id', $schedule->id]);
    }
}
