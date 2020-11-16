<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Player;
use common\models\db\PlayerPosition;
use yii\db\ActiveQuery;

/**
 * Class PlayerQuery
 * @package frontend\models\queries
 */
class PlayerQuery
{
    /**
     * @param int $playerId
     * @return Player|null
     */
    public static function getPlayerById(int $playerId): ?Player
    {
        /**
         * @var Player $result
         */
        $result = Player::find()
            ->where(['id' => $playerId])
            ->limit(1)
            ->one();
        return $result;
    }

    /**
     * @param int $teamId
     * @return ActiveQuery
     */
    public static function getPlayerTeamList(int $teamId): ActiveQuery
    {
        return Player::find()
            ->joinWith(['playerPositions'])
            ->where([
                'or',
                ['team_id' => $teamId],
                ['loan_team_id' => $teamId]
            ]);
    }
}
