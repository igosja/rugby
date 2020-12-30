<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class UserRating
 * @package common\models\db
 *
 * @property int $id
 * @property int $auto
 * @property int $collision_loose
 * @property int $collision_win
 * @property int $draw
 * @property int $draw_equal
 * @property int $draw_strong
 * @property int $draw_weak
 * @property int $game
 * @property int $loose
 * @property int $loose_equal
 * @property int $loose_strong
 * @property int $loose_super
 * @property int $loose_weak
 * @property float $rating
 * @property int $season_id
 * @property int $user_id
 * @property int $vs_super
 * @property int $vs_rest
 * @property int $win
 * @property int $win_equal
 * @property int $win_strong
 * @property int $win_super
 * @property int $win_weak
 *
 * @property-read Season $season
 * @property-read User $user
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

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['user_id'], 'required'],
            [['season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['rating'], 'number', 'min' => -9999, 'max' => 9999],
            [
                [
                    'auto',
                    'collision_loose',
                    'collision_win',
                    'draw',
                    'draw_equal',
                    'draw_strong',
                    'draw_weak',
                    'game',
                    'loose',
                    'loose_equal',
                    'loose_strong',
                    'loose_super',
                    'loose_weak',
                    'user_id',
                    'vs_super',
                    'vs_rest',
                    'win',
                    'win_equal',
                    'win_strong',
                    'win_super',
                    'win_weak',
                ],
                'integer',
                'min' => 0
            ],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
