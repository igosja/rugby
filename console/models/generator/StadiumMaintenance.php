<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Stadium;
use yii\db\Expression;

/**
 * Class StadiumMaintenance
 * @package console\models\generator
 */
class StadiumMaintenance
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Stadium::updateAll(['maintenance' => new Expression('ROUND(POW(capacity/60, 2))')]);
    }
}