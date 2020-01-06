<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class UserRating
 * @package common\models\db
 *
 * @property int $user_rating_id
 * @property int $user_rating_auto
 * @property int $user_rating_collision_loose
 * @property int $user_rating_collision_win
 * @property int $user_rating_game
 * @property int $user_rating_loose
 * @property int $user_rating_loose_equal
 * @property int $user_rating_loose_overtime
 * @property int $user_rating_loose_overtime_equal
 * @property int $user_rating_loose_overtime_strong
 * @property int $user_rating_loose_overtime_weak
 * @property int $user_rating_loose_strong
 * @property int $user_rating_loose_super
 * @property int $user_rating_loose_weak
 * @property float $user_rating_rating
 * @property int $user_rating_season_id
 * @property int $user_rating_user_id
 * @property int $user_rating_vs_super
 * @property int $user_rating_vs_rest
 * @property int $user_rating_win
 * @property int $user_rating_win_equal
 * @property int $user_rating_win_overtime
 * @property int $user_rating_win_overtime_equal
 * @property int $user_rating_win_overtime_strong
 * @property int $user_rating_win_overtime_weak
 * @property int $user_rating_win_strong
 * @property int $user_rating_win_super
 * @property int $user_rating_win_weak
 */
class UserRating extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_rating}}';
    }
}
