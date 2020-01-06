<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class GameVote
 * @package common\models\db
 *
 * @property int $game_vote_id
 * @property int $game_vote_game_id
 * @property int $game_vote_rating
 * @property int $game_vote_user_id
 */
class GameVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%game_vote}}';
    }
}
