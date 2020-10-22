<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Scout
 * @package common\models\db
 *
 * @property int $id
 * @property bool $is_school
 * @property bool $is_style
 * @property int $percent
 * @property int $player_id
 * @property int $ready
 * @property int $season_id
 * @property int $team_id
 *
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Team $team
 */
class Scout extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%scout}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['player_id', 'season_id', 'team_id'], 'required'],
            [['is_school', 'is_style'], 'boolean'],
            [['percent'], 'min' => 0, 'max' => 100],
            [['season_id'], 'min' => 1, 'max' => 999],
            [['player_id', 'ready', 'team_id'], 'min' => 1],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
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
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
