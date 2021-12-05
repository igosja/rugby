<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;
use yii\db\Expression;

/**
 * Class PlayerPowerChange
 * @package console\models\newSeason
 */
class PlayerPowerChange
{
    /**
     * @return void
     */
    public function execute()
    {
        Player::updateAll(
            ['power_nominal' => new Expression('`power_nominal`+27-`age`')],
            ['<=', 'age', 23]
        );
        Player::updateAll(
            ['power_nominal' => new Expression('ROUND(`power_nominal`*(100-(`age`-30)*5)/100)')],
            ['between', 'age', 31, 34]
        );
    }
}
