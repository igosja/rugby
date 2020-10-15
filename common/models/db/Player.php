<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Player
 * @package common\models\db
 *
 * @property int $id
 * @property int $age
 * @property int $country_id
 * @property int $date_no_action
 * @property int $game_row
 * @property int $game_row_old
 * @property int $injury_day
 * @property int $is_injury
 * @property int $is_no_deal
 * @property int $loan_day
 * @property int $loan_team_id
 * @property int $mood_id
 * @property int $name_id
 * @property int $national_id
 * @property int $national_squad_id
 * @property int $order
 * @property int $physical_id
 * @property int $position_id
 * @property int $power_nominal
 * @property int $power_nominal_s
 * @property int $power_old
 * @property int $power_real
 * @property int $price
 * @property int $salary
 * @property int $school_team_id
 * @property int $squad_id
 * @property int $style_id
 * @property int $surname_id
 * @property int $team_id
 * @property int $tire
 * @property int $training_ability
 *
 * @property-read Country $country
 * @property-read Team $loanTeam
 * @property-read Mood $mood
 * @property-read Name $name
 * @property-read National $national
 * @property-read Squad $nationalSquad
 * @property-read Physical $physical
 * @property-read Position $position
 * @property-read Team $schoolTeam
 * @property-read Squad $squad
 * @property-read Style $style
 * @property-read Surname $surname
 * @property-read Team $team
 */
class Player extends AbstractActiveRecord
{
    public const AGE_READY_FOR_PENSION = 34;
    public const START_NUMBER_OF_PLAYERS = 30;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%player}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                [
                    'age',
                    'country_id',
                    'name_id',
                    'physical_id',
                    'position_id',
                    'school_team_id',
                    'style_id',
                    'surname_id',
                    'team_id',
                ],
                'required'
            ],
            [['is_injury', 'is_no_deal'], 'boolean'],
            [
                ['loan_day', 'mood_id', 'national_squad_id', 'squad_id', 'style_id', 'training_ability'],
                'integer',
                'min' => 1,
                'max' => 9
            ],
            [['age', 'physical_id', 'position_id', 'tire'], 'integer', 'min' => 1, 'max' => 99],
            [['game_row', 'game_row_old'], 'integer', 'min' => -99, 'max' => 99],
            [
                ['country_id', 'national_id', 'order', 'power_nominal', 'power_nominal_s', 'power_old', 'power_real'],
                'integer',
                'min' => 0,
                'max' => 999
            ],
            [
                ['date_no_action', 'name_id', 'price', 'salary', 'school_team_id', 'surname_id', 'team_id'],
                'integer',
                'min' => 0
            ],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['loan_team_id'], 'exist', 'targetRelation' => 'loanTeam'],
            [['mood_id'], 'exist', 'targetRelation' => 'mood'],
            [['name_id'], 'exist', 'targetRelation' => 'name'],
            [['national_id'], 'exist', 'targetRelation' => 'national'],
            [['national_squad_id'], 'exist', 'targetRelation' => 'nationalSquad'],
            [['physical_id'], 'exist', 'targetRelation' => 'physical'],
            [['position_id'], 'exist', 'targetRelation' => 'position'],
            [['school_team_id'], 'exist', 'targetRelation' => 'schoolTeam'],
            [['squad_id'], 'exist', 'targetRelation' => 'squad'],
            [['style_id'], 'exist', 'targetRelation' => 'style'],
            [['surname_id'], 'exist', 'targetRelation' => 'surname'],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getLoanTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'loan_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMood(): ActiveQuery
    {
        return $this->hasOne(Mood::class, ['id' => 'mood_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getName(): ActiveQuery
    {
        return $this->hasOne(Name::class, ['id' => 'name_id'])->cache();
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
    public function getNationalSquad(): ActiveQuery
    {
        return $this->hasOne(Squad::class, ['id' => 'national_squad_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhysical(): ActiveQuery
    {
        return $this->hasOne(Physical::class, ['id' => 'physical_id']);
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
    public function getSchoolTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'school_team_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSquad(): ActiveQuery
    {
        return $this->hasOne(Squad::class, ['id' => 'squad_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['id' => 'style_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSurname(): ActiveQuery
    {
        return $this->hasOne(Surname::class, ['id' => 'surname_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
