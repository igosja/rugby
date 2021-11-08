<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Season;
use Exception;

/**
 * Class InsertNewSeason
 * @package console\models\newSeason
 */
class InsertNewSeason
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $season = Season::find()
            ->andWhere(['id' => Season::getCurrentSeason() + 1])
            ->limit(1)
            ->one();
        if (!$season) {
            $season = new Season();
            $season->id = Season::getCurrentSeason() + 1;
        }
        $season->is_future = false;
        $season->save();

        $season = Season::find()
            ->andWhere(['id' => Season::getCurrentSeason() + 1])
            ->limit(1)
            ->one();
        if (!$season) {
            $season = new Season();
            $season->id = Season::getCurrentSeason() + 1;
        }
        $season->is_future = true;
        $season->save();
    }
}
