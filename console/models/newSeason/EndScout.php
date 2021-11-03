<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Scout;

/**
 * Class EndScout
 * @package console\models\newSeason
 */
class EndScout
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Scout::updateAll(
            ['percent' => 100, 'ready' => time()],
            ['ready' => null]
        );
    }
}