<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class StatisticType
 * @package common\models\db
 *
 * @property int $id
 * @property string $name
 * @property int $order
 * @property string $select_field
 * @property int $statistic_chapter_id
 *
 * @property-read StatisticChapter $statisticChapter
 */
class StatisticType extends AbstractActiveRecord
{
    public const PLAYER_ASSIST = 14;
    public const PLAYER_ASSIST_POWER = 15;
    public const PLAYER_ASSIST_SHORT = 16;
    public const PLAYER_SHOOTOUT_WIN = 17;
    public const PLAYER_FACE_OFF = 18;
    public const PLAYER_FACE_OFF_PERCENT = 19;
    public const PLAYER_FACE_OFF_WIN = 20;
    public const PLAYER_GAME = 21;
    public const PLAYER_LOOSE = 22;
    public const PLAYER_PASS = 23;
    public const PLAYER_PASS_PER_GAME = 24;
    public const PLAYER_PENALTY = 25;
    public const PLAYER_PLUS_MINUS = 26;
    public const PLAYER_POINT = 27;
    public const PLAYER_SAVE = 28;
    public const PLAYER_SAVE_PERCENT = 29;
    public const PLAYER_SCORE = 30;
    public const PLAYER_SCORE_DRAW = 31;
    public const PLAYER_SCORE_POWER = 32;
    public const PLAYER_SCORE_SHORT = 33;
    public const PLAYER_SCORE_SHOT_PERCENT = 34;
    public const PLAYER_SCORE_WIN = 35;
    public const PLAYER_SHOT = 36;
    public const PLAYER_SHOT_GK = 37;
    public const PLAYER_SHOT_PER_GAME = 38;
    public const PLAYER_SHUTOUT = 39;
    public const PLAYER_WIN = 40;
    public const TEAM_NO_PASS = 1;
    public const TEAM_NO_SCORE = 2;
    public const TEAM_LOOSE = 3;
    public const TEAM_LOOSE_SHOOTOUT = 4;
    public const TEAM_LOOSE_OVER = 5;
    public const TEAM_PASS = 6;
    public const TEAM_SCORE = 7;
    public const TEAM_PENALTY = 8;
    public const TEAM_PENALTY_OPPONENT = 9;
    public const TEAM_WIN = 10;
    public const TEAM_WIN_SHOOTOUT = 11;
    public const TEAM_WIN_OVER = 12;
    public const TEAM_WIN_PERCENT = 13;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_type}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'order', 'select_field', 'statistic_chapter_id'], 'required'],
            [['name', 'select_field'], 'trim'],
            [['name', 'select_field'], 'string', 'max' => 255],
            [['statistic_chapter_id'], 'integer', 'min' => 1, 'max' => 9],
            [['order'], 'integer', 'min' => 1, 'max' => 99],
            [['statistic_chapter_id'], 'exist', 'targetRelation' => 'statisticChapter'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStatisticChapter(): ActiveQuery
    {
        return $this->hasOne(StatisticChapter::class, ['id' => 'statistic_chapter_id']);
    }
}
