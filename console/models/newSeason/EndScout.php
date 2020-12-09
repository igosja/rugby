<?php

namespace console\models\newSeason;

use common\models\Scout;

/**
 * Class EndScout
 * @package console\models\newSeason
 */
class EndScout
{
    /**
     * @return void
     */
    public function execute()
    {
        Scout::updateAll(
            ['scout_percent' => 100, 'scout_ready' => time()],
            ['scout_ready' => 0]
        );
    }
}