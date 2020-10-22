<?php

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Training
 * @package common\models\db
 *
 * @property int $id
 * @property bool $is_power
 * @property int $percent
 * @property int $player_id
 * @property int $position_id
 * @property int $ready
 * @property int $season_id
 * @property int $special_id
 * @property int $team_id
 *
 * @property-read Player $player
 * @property-read Position $position
 * @property-read Season $season
 * @property-read Special $special
 * @property-read Team $team
 */
class Training extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%training}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['player_id', 'season_id', 'team_id'], 'required'],
            [['is_power'], AtLeastValidator::class, 'in' => ['is_power', 'position_id', 'special_id']],
            [['is_power'], 'boolean'],
            [['position_id', 'special_id'], 'integer', 'min' => 1, 'max' => 99],
            [['percent'], 'integer', 'min' => 0, 'max' => 100],
            [['season_id'], 'integer', 'min' => 1, 'max' => 999],
            [['player_id', 'ready', 'team_id'], 'integer', 'min' => 1],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['special_id'], 'exist', 'targetRelation' => 'special'],
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
    public function getPosition(): ActiveQuery
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
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
    public function getSpecial(): ActiveQuery
    {
        return $this->hasOne(Special::class, ['id' => 'special_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
