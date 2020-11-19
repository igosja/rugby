<?php

namespace console\models\newSeason;

use common\models\Player;
use yii\db\Expression;

/**
 * Class RandPhysical
 * @package console\models\newSeason
 */
class RandPhysical
{
    /**
     * @return void
     */
    public function execute()
    {
        Player::updateAll(['player_physical_id' => new Expression('FLOOR(1+RAND()*20)')], ['!=', 'player_team_id', 0]);
    }
}
