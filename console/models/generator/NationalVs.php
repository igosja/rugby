<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\National;
use Exception;

/**
 * Class NationalVs
 * @package console\models\generator
 */
class NationalVs
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $nationalArray = National::find()
            ->where(['!=', 'id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();

        foreach ($nationalArray as $national) {
            /**
             * @var National $national
             */
            $national->updatePower();
        }
    }
}
