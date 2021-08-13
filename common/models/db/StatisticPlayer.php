<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class StatisticPlayer
 * @package common\models\db
 *
 * @property int $id
 * @property int $clean_break
 * @property int $defender_beaten
 * @property int $division_id
 * @property int $draw
 * @property int $federation_id
 * @property int $game
 * @property int $loose
 * @property int $metre_gained
 * @property int $national_id
 * @property int $pass
 * @property int $player_id
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
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Team $team
 * @property-read TournamentType $tournamentType
 */
class StatisticPlayer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_player}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['player_id', 'tournament_type_id'], 'required'],
            [['national_id'], AtLeastValidator::class, 'in' => ['national_id', 'team_id']],
            [['division_id', 'red_card', 'tournament_type_id'], 'integer', 'min' => 0, 'max' => 9],
            [
                [
                    'clean_break',
                    'defender_beaten',
                    'draw',
                    'game',
                    'loose',
                    'turnover_won',
                    'try',
                    'win',
                    'yellow_card',
                ],
                'integer',
                'min' => 0,
                'max' => 99
            ],
            [['federation_id', 'point', 'season_id', 'tackle'], 'integer', 'min' => 0, 'max' => 999],
            [['metre_gained', 'pass'], 'integer', 'min' => 0, 'max' => 9999],
            [['national_id'], 'integer', 'min' => 0, 'max' => 99999],
            [['player_id', 'team_id'], 'integer', 'min' => 0],
            [['division_id'], 'exist', 'targetRelation' => 'division'],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
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

    /**
     * @return ActiveQuery
     */
    public function getTournamentType(): ActiveQuery
    {
        return $this->hasOne(TournamentType::class, ['id' => 'tournament_type_id']);
    }
}
