<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Federation;
use Exception;

/**
 * Class PresidentViceFire
 * @package console\models\generator
 */
class PresidentViceFire
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        $federationArray = Federation::find()
            ->joinWith(['viceUser'])
            ->where(['not', ['vice_user_id' => null]])
            ->andWhere(['<', 'date_login', time() - 1296000])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($federationArray as $federation) {
            /**
             * @var Federation $federation
             */
            $federation->fireVicePresident();
        }
    }
}
