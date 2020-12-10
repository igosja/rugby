<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use rmrevin\yii\fontawesome\FAS;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Lineup
 * @package common\models\db
 *
 * @property int $id
 * @property int $age
 * @property int $clean_break
 * @property int $conversion
 * @property int $defender_beaten
 * @property int $drop_goal
 * @property int $game_id
 * @property bool $is_captain
 * @property int $metre_gained
 * @property int $national_id
 * @property int $pass
 * @property int $penalty_kick
 * @property int $player_id
 * @property int $point
 * @property int $position_id
 * @property int $power_change
 * @property int $power_nominal
 * @property int $power_real
 * @property int $red_card
 * @property int $tackle
 * @property int $team_id
 * @property int $try
 * @property int $turnover_won
 * @property int $yellow_card
 *
 * @property-read Game $game
 * @property-read LineupSpecial[] $lineupSpecials
 * @property-read National $national
 * @property-read Player $player
 * @property-read Team $team
 */
class Lineup extends AbstractActiveRecord
{
    public const GAME_QUANTITY = 15;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%lineup}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['game_id', 'player_id', 'position_id'], 'required'],
            [['national_id'], AtLeastValidator::class, 'in' => ['national_id', 'team_id']],
            [['is_captain'], 'boolean'],
            [['drop_goal', 'red_card', 'try', 'yellow_card'], 'integer', 'min' => 0, 'max' => 9],
            [
                [
                    'clean_break',
                    'conversion',
                    'penalty_kick',
                    'point',
                    'position_id',
                    'tackle',
                    'turnover_won',
                ],
                'integer',
                'min' => 0,
                'max' => 99,
            ],
            [
                ['metre_gained', 'national_id', 'pass', 'power_nominal', 'power_real'],
                'integer',
                'min' => 0,
                'max' => 999,
            ],
            [['game_id', 'player_id', 'team_id'], 'integer', 'min' => 1],
            [['game_id'], 'exist', 'targetRelation' => 'game'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return string
     */
    public function iconCaptain(): string
    {
        $result = '';
        if ($this->is_captain) {
            $result = FAS::icon(FAS::_COPYRIGHT, ['title' => 'Капитан']);
        }
        return $result;
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function iconPowerChange(): string
    {
        $result = '';
        if ($this->power_change > 0) {
            $result = FAS::icon(FAS::_PLUS_SQUARE, ['title' => '+1 балл по результатам матча'])->addCssClass('font-green');
        } elseif ($this->power_change < 0) {
            $result = FAS::icon(FAS::_PLUS_SQUARE, ['title' => '-1 балл по результатам матча'])->addCssClass('font-red');
        }
        return $result;
    }

    /**
     * @return string
     */
    public function special(): string
    {
        $result = [];
        foreach ($this->lineupSpecials as $lineupSpecial) {
            $result[] = Html::tag(
                'span',
                $lineupSpecial->special->name . $lineupSpecial->level,
                ['title' => $lineupSpecial->special->text]
            );
        }
        return implode(' ', $result);
    }

    /**
     * @return ActiveQuery
     */
    public function getGame(): ActiveQuery
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLineupSpecials(): ActiveQuery
    {
        return $this->hasMany(LineupSpecial::class, ['lineup_id' => 'id']);
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
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
