<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class LineupTemplate
 * @package common\models\db
 *
 * @property int $id
 * @property bool $is_captain
 * @property string $name
 * @property int $national_id
 * @property int $player_1_id
 * @property int $player_2_id
 * @property int $player_3_id
 * @property int $player_4_id
 * @property int $player_5_id
 * @property int $player_6_id
 * @property int $player_7_id
 * @property int $player_8_id
 * @property int $player_9_id
 * @property int $player_10_id
 * @property int $player_11_id
 * @property int $player_12_id
 * @property int $player_13_id
 * @property int $player_14_id
 * @property int $player_15_id
 * @property int $rudeness_id
 * @property int $style_id
 * @property int $tactic_id
 * @property int $team_id
 *
 * @property-read National $national
 * @property-read Rudeness $rudeness
 * @property-read Style $style
 * @property-read Tactic $tactic
 * @property-read Team $team
 */
class LineupTemplate extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%lineup_template}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'name',
                    'player_1_id',
                    'player_2_id',
                    'player_3_id',
                    'player_4_id',
                    'player_5_id',
                    'player_6_id',
                    'player_7_id',
                    'player_8_id',
                    'player_9_id',
                    'player_10_id',
                    'player_11_id',
                    'player_12_id',
                    'player_13_id',
                    'player_14_id',
                    'player_15_id',
                    'rudeness_id',
                    'style_id',
                    'tactic_id',
                ],
                'required'
            ],
            [['national_id'], AtLeastValidator::class, 'in' => ['national_id', 'team_id']],
            [['is_captain'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['rudeness_id', 'style_id', 'tactic_id'], 'integer', 'min' => 1, 'max' => 9],
            [['national_id'], 'integer', 'min' => 1, 'max' => 999],
            [
                [
                    'player_1_id',
                    'player_2_id',
                    'player_3_id',
                    'player_4_id',
                    'player_5_id',
                    'player_6_id',
                    'player_7_id',
                    'player_8_id',
                    'player_9_id',
                    'player_10_id',
                    'player_11_id',
                    'player_12_id',
                    'player_13_id',
                    'player_14_id',
                    'player_15_id',
                ],
                'integer',
                'min' => 1
            ],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [
                [
                    'player_1_id',
                    'player_2_id',
                    'player_3_id',
                    'player_4_id',
                    'player_5_id',
                    'player_6_id',
                    'player_7_id',
                    'player_8_id',
                    'player_9_id',
                    'player_10_id',
                    'player_11_id',
                    'player_12_id',
                    'player_13_id',
                    'player_14_id',
                    'player_15_id',
                ],
                'exist',
                'targetClass' => Player::class,
                'targetAttribute' => 'id',
            ],
            [['rudeness_id'], 'exist', 'targetRelation' => 'rudeness'],
            [['style_id'], 'exist', 'targetRelation' => 'style'],
            [['tactic_id'], 'exist', 'targetRelation' => 'tactic'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRudeness(): ActiveQuery
    {
        return $this->hasOne(Rudeness::class, ['id' => 'rudeness_id']);
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
    public function getTactic(): ActiveQuery
    {
        return $this->hasOne(Tactic::class, ['id' => 'tactic_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
