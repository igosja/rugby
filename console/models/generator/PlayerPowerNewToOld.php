<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;
use yii\db\Expression;

/**
 * Class PlayerPowerNewToOld
 * @package console\models\generator
 */
class PlayerPowerNewToOld
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Player::updateAll(
            ['power_old' => new Expression('power_nominal')],
            [
                'and',
                ['!=', 'power_old', new Expression('power_nominal')],
                ['<=', 'age', Player::AGE_READY_FOR_PENSION]
            ]
        );
    }
}