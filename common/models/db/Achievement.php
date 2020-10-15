<?php

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Achievement
 * @package common\models\db
 *
 * @property int $id
 * @property int $country_id
 * @property int $division_id
 * @property int $national_id
 * @property int $place
 * @property int $season_id
 * @property int $stage_id
 * @property int $team_id
 * @property int $tournament_type_id
 * @property int $user_id
 *
 * @property-read Country $country
 * @property-read Division $division
 * @property-read National $national
 * @property-read Season $season
 * @property-read Stage $stage
 * @property-read Team $team
 * @property-read TournamentType $tournamentType
 * @property-read User $user
 */
class Achievement extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%achievement}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['season_id', 'tournament_type_id'], 'required'],
            [
                [
                    'country_id',
                    'division_id',
                    'national_id',
                    'season_id',
                    'stage_id',
                    'team_id',
                    'tournament_type_id',
                    'user_id',
                ],
                'integer',
                'min' => 0,
            ],
            [['national_id'], AtLeastValidator::class, 'in' => ['national_id', 'team_id']],
            [['place'], AtLeastValidator::class, 'in' => ['place', 'stage_id']],
            [['place'], 'integer', 'min' => 1, 'max' => 99],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['division_id'], 'exist', 'targetRelation' => 'division'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
            [['stage_id'], 'exist', 'targetRelation' => 'stage'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
            [['tournament_type_id'], 'exist', 'targetRelation' => 'tournamentType'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
