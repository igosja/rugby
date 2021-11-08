<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Federation;

/**
 * Class CountryAutoReset
 * @package console\models\newSeason
 */
class CountryAutoReset
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Federation::updateAll(['auto' => 0], ['!=', 'auto', 0]);
    }
}
