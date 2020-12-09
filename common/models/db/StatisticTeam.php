<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class StatisticTeam
 * @package common\models\db
 *
 * @property int $id
 * @property int $carry
 * @property int $clean_break
 * @property int $defender_beaten
 * @property int $division_id
 * @property int $draw
 * @property int $drop_goal
 * @property int $federation_id
 * @property int $game
 * @property int $loose
 * @property int $metre_gained
 * @property int $national_id
 * @property int $pass
 * @property int $penalty_conceded
 * @property int $point
 * @property int $red_card
 * @property int $season_id
 * @property int $tackle
 * @property int $team_id
 * @property int $tournament_type_id
 * @property int $turnover_won
 * @property int $try
 * @property int $win
 * @property int $yellow_card
 *
 * @property-read Division $division
 * @property-read Federation $federation
 * @property-read National $national
 * @property-read Season $season
 * @property-read Team $team
 * @property-read TournamentType $tournamentType
 */
class StatisticTeam extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_team}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['tournament_type_id'], 'required'],
            [['national_id'], AtLeastValidator::class, 'in' => ['national_id', 'team_id']],
            [['division_id', 'tournament_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [
                [
                    'draw',
                    'drop_goal',
                    'game',
                    'loose',
                    'red_card',
                    'try',
                    'win',
                    'yellow_card',
                ],
                'integer',
                'min' => 0,
                'max' => 99
            ],
            [
                [
                    'clean_break',
                    'defender_beaten',
                    'federation_id',
                    'penalty_conceded',
                    'point',
                    'season_id',
                    'turnover_won',
                ],
                'integer',
                'min' => 0,
                'max' => 999,
            ],
            [['carry', 'pass', 'tackle'], 'integer', 'min' => 0, 'max' => 9999],
            [['metre_gained', 'national_id'], 'integer', 'min' => 0, 'max' => 99999],
            [['team_id'], 'integer', 'min' => 0],
            [['division_id'], 'exist', 'targetRelation' => 'division'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['tournament_type_id'], 'exist', 'targetRelation' => 'tournamentType'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDivision(): ActiveQuery
    {
        return $this->hasOne(Division::class, ['id' => 'division_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFederation(): ActiveQuery
    {
        return $this->hasOne(Federation::class, ['id' => 'federation_id']);
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

    /**
     * @return ActiveQuery
     */
    public function getTournamentType(): ActiveQuery
    {
        return $this->hasOne(TournamentType::class, ['id' => 'tournament_type_id']);
    }
}
