<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\National;
use Exception;

/**
 * Class NationalViceFire
 * @package console\models\generator
 */
class NationalViceFire
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $nationalArray = National::find()
            ->joinWith(['viceUser'])
            ->where(['not', ['vice_user_id' => null]])
            ->andWhere(['<', 'date_login', time() - 1296000])
            ->orderBy(['national.id' => SORT_ASC])
            ->each();
        foreach ($nationalArray as $national) {
            /**
             * @var National $national
             */
            $national->fireVice();
        }
    }
}
