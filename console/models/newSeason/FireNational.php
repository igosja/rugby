<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Attitude;
use common\models\db\FireReason;
use common\models\db\National;
use common\models\db\Team;
use Exception;

/**
 * Class FireNational
 * @package console\models\newSeason
 */
class FireNational
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $nationalArray = National::find()
            ->where([
                'or',
                ['!=', 'user_id', 0],
                ['!=', 'vice_user_id', 0]
            ])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        foreach ($nationalArray as $national) {
            if ($national->viceUser) {
                $national->fireVice();
            }
            if ($national->user) {
                $national->fireUser(FireReason::FIRE_REASON_NEW_SEASON);
            }
        }

        Team::updateAll([
            'national_attitude_id' => Attitude::NEUTRAL,
            'u21_attitude_id' => Attitude::NEUTRAL,
            'u19_attitude_id' => Attitude::NEUTRAL,
        ]);
    }
}
