<?php

// TODO refactor

namespace console\models\newSeason;

use common\models\db\Player;
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
    public function execute(): void
    {
        Player::updateAll(['physical_id' => new Expression('FLOOR(1+RAND()*20)')], ['!=', 'team_id', 0]);
    }
}
