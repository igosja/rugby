<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class GameComment
 * @package common\models\db
 *
 * @property int $game_comment_id
 * @property int $game_comment_check
 * @property int $game_comment_date
 * @property int $game_comment_game_id
 * @property string $game_comment_text
 * @property int $game_comment_user_id
 */
class GameComment extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%game_comment}}';
    }
}
