<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\National;
use common\models\db\Stadium;
use Exception;

/**
 * Class NationalStadium
 * @package console\models\generator
 */
class NationalStadium
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $nationalArray = National::find()
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($nationalArray as $national) {
            /**
             * @var National $national
             * @var Stadium $stadium
             */
            $stadium = Stadium::find()
                ->joinWith(['city.country.federation'], false)
                ->where(['federation.id' => $national->federation_id])
                ->orderBy(['capacity' => SORT_DESC, 'stadium.id' => SORT_ASC])
                ->offset($national->national_type_id - 1)
                ->limit(1)
                ->one();
            if (!$stadium) {
                continue;
            }

            $national->stadium_id = $stadium->id;
            $national->save(true, ['stadium_id']);
        }
    }
}
