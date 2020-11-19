<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Country;

/**
 * Class CountryAutoReset
 * @package console\models\newSeason
 */
class CountryAutoReset
{
    /**
     * @return void
     */
    public function execute()
    {
        Country::updateAll(['country_auto' => 0], ['!=', 'country_auto', 0]);
    }
}
