<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class School
 * @package common\models\db
 *
 * @property int $id
 * @property int $day
 * @property bool $is_with_special
 * @property bool $is_with_special_request
 * @property bool $is_with_style
 * @property bool $is_with_style_request
 * @property int $position_id
 * @property int $ready
 * @property int $season_id
 * @property int $special_id
 * @property int $style_id
 * @property int $team_id
 *
 * @property-read Position $position
 * @property-read Season $season
 * @property-read Special $special
 * @property-read Style $style
 * @property-read Team $team
 */
class School extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%school}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['position_id', 'season_id', 'team_id'], 'required'],
            [['is_with_special', 'is_with_special_request', 'is_with_style', 'is_with_style_request'], 'boolean'],
            [['style_id'], 'min' => 1, 'max' => 9],
            [['day', 'position_id', 'special_id'], 'min' => 1, 'max' => 99],
            [['season_id'], 'min' => 1, 'max' => 999],
            [['ready', 'team_id'], 'min' => 1],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['special_id'], 'exist', 'targetRelation' => 'special'],
            [['style_id'], 'exist', 'targetRelation' => 'style'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
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
    public function getStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['id' => 'style_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
