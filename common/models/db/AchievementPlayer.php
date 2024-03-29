<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\db\ActiveQuery;

/**
 * Class AchievementPlayer
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $division_id
 * @property int $national_id
 * @property int $place
 * @property int $player_id
 * @property int $season_id
 * @property int $stage_id
 * @property int $team_id
 * @property int $tournament_type_id
 *
 * @property-read Federation $federation
 * @property-read Division $division
 * @property-read National $national
 * @property-read Player $player
 * @property-read Season $season
 * @property-read Stage $stage
 * @property-read Team $team
 * @property-read TournamentType $tournamentType
 */
class AchievementPlayer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%achievement_player}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['player_id', 'season_id', 'tournament_type_id'], 'required'],
            [
                [
                    'federation_id',
                    'division_id',
                    'national_id',
                    'player_id',
                    'season_id',
                    'stage_id',
                    'team_id',
                    'tournament_type_id',
                ],
                'integer',
                'min' => 0,
            ],
            [['place'], 'integer', 'min' => 1, 'max' => 99],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['division_id'], 'exist', 'targetRelation' => 'division'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['player_id'], 'exist', 'targetRelation' => 'player'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['stage_id'], 'exist', 'targetRelation' => 'stage'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['tournament_type_id'], 'exist', 'targetRelation' => 'tournamentType'],
        ];
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        if ($this->place) {
            $result = $this->place;
            if ($this->place <= 3) {
                if (1 === $this->place) {
                    $color = 'gold';
                } elseif (2 === $this->place) {
                    $color = 'silver';
                } else {
                    $color = '#6A3805';
                }
                $result .= ' ' . FAS::icon(FontAwesome::_TROPHY, ['style' => ['color' => $color]]);
            }
        } elseif ($this->stage) {
            $result = $this->stage->name;
            if (in_array($this->stage_id, [Stage::FINAL_GAME, Stage::SEMI], true)) {
                if (Stage::FINAL_GAME === $this->stage_id) {
                    $color = 'silver';
                } else {
                    $color = '#6A3805';
                }
                $result .= ' ' . FAS::icon(FontAwesome::_TROPHY, ['style' => ['color' => $color]]);
            }
        } else {
            $result = FAS::icon(FontAwesome::_TROPHY, ['style' => ['color' => 'gold']]);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getTournament(): string
    {
        $result = $this->tournamentType->name;

        if ($this->federation_id || $this->division_id) {
            $additional = [];

            if ($this->federation_id) {
                $additional[] = $this->federation->country->name;
            }

            if ($this->division_id) {
                $additional[] = $this->division->name;
            }

            $result .= ' (' . implode(', ', $additional) . ')';
        }

        return $result;
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
    public function getDivision(): ActiveQuery
    {
        return $this->hasOne(Division::class, ['id' => 'division_id']);
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
    public function getStage(): ActiveQuery
    {
        return $this->hasOne(Stage::class, ['id' => 'stage_id']);
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

    /**
     * @return ActiveQuery
     */
    public function getPlayer(): ActiveQuery
    {
        return $this->hasOne(Player::class, ['id' => 'player']);
    }
}
