<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Federation;
use common\models\db\Stadium;
use Exception;

/**
 * Class CountryStadiumCapacity
 * @package console\models\generator
 */
class CountryStadiumCapacity
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $federationArray = Federation::find()
            ->joinWith(['country.cities.stadiums.team'])
            ->where(['!=', 'team.id', 0])
            ->groupBy(['federation.id'])
            ->orderBy(['federation.id' => SORT_ASC])
            ->each();
        foreach ($federationArray as $federation) {
            /**
             * @var Federation $federation
             */
            $capacity = Stadium::find()
                ->joinWith(['city'])
                ->where(['country_id' => $federation->country_id])
                ->average('capacity');

            $federation->stadium_capacity = round($capacity);
            $federation->save();
        }
    }
}
