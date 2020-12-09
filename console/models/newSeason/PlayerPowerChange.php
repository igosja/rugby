<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\Player;
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
        Player::updateAll([
            'player_power_nominal' => new Expression('`player_power_nominal`+27-`player_age`')
        ],
            ['<=', 'player_age', 23]
        );
        Player::updateAll([
            'player_power_nominal' => new Expression('ROUND(`player_power_nominal`*(100-(`player_age`-34)*5)/100)')
        ],
            ['between', 'player_age', 35, 39]
        );
    }
}
