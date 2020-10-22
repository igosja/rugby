<?php

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Game
 * @package common\models\db
 *
 * @property int $id
 * @property int $bonus_home
 * @property int $guest_auto
 * @property int $guest_collision
 * @property int $guest_conversion
 * @property int $guest_drop_goal
 * @property int $guest_forecast
 * @property int $guest_mood_id
 * @property int $guest_national_id
 * @property int $guest_optimality_1
 * @property int $guest_optimality_2
 * @property int $guest_penalty
 * @property int $guest_plus_minus
 * @property float $guest_plus_minus_competition
 * @property float $guest_plus_minus_mood
 * @property float $guest_plus_minus_optimality_1
 * @property float $guest_plus_minus_optimality_2
 * @property float $guest_plus_minus_power
 * @property float $guest_plus_minus_score
 * @property int $guest_points
 * @property int $guest_possession
 * @property int $guest_power
 * @property int $guest_power_percent
 * @property int $guest_red_card
 * @property int $guest_rudeness_id
 * @property int $guest_style_id
 * @property int $guest_tactic_id
 * @property int $guest_team_id
 * @property float $guest_teamwork
 * @property int $guest_try
 * @property int $guest_user_id
 * @property int $guest_yellow_card
 * @property int $home_auto
 * @property int $home_collision
 * @property int $home_conversion
 * @property int $home_drop_goal
 * @property int $home_forecast
 * @property int $home_mood_id
 * @property int $home_national_id
 * @property int $home_optimality_1
 * @property int $home_optimality_2
 * @property int $home_penalty
 * @property int $home_plus_minus
 * @property float $home_plus_minus_competition
 * @property float $home_plus_minus_mood
 * @property float $home_plus_minus_optimality_1
 * @property float $home_plus_minus_optimality_2
 * @property float $home_plus_minus_power
 * @property float $home_plus_minus_score
 * @property int $home_points
 * @property int $home_possession
 * @property int $home_power
 * @property int $home_power_percent
 * @property int $home_red_card
 * @property int $home_rudeness_id
 * @property int $home_style_id
 * @property int $home_tactic_id
 * @property int $home_team_id
 * @property float $home_teamwork
 * @property int $home_try
 * @property int $home_user_id
 * @property int $home_yellow_card
 * @property int $played
 * @property int $ticket_price
 * @property int $schedule_id
 * @property int $stadium_capacity
 * @property int $stadium_id
 * @property int $visitor
 * @property int $weather_id
 *
 * @property-read Mood $guestMood
 * @property-read National $guestNational
 * @property-read Rudeness $guestRudeness
 * @property-read Style $guestStyle
 * @property-read Tactic $guestTactic
 * @property-read Team $guestTeam
 * @property-read User $guestUser
 * @property-read Mood $homeMood
 * @property-read National $homeNational
 * @property-read Rudeness $homeRudeness
 * @property-read Style $homeStyle
 * @property-read Tactic $homeTactic
 * @property-read Team $homeTeam
 * @property-read User $homeUser
 * @property-read Schedule $schedule
 * @property-read Stadium $stadium
 * @property-read Weather $weather
 */
class Game extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%game}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['schedule_id', 'weather_id'], 'required'],
            [['guest_national_id'], AtLeastValidator::class, 'in' => ['guest_national_id', 'guest_team_id']],
            [['home_national_id'], AtLeastValidator::class, 'in' => ['home_national_id', 'home_team_id']],
            [['bonus_home', 'guest_auto', 'home_auto'], 'boolean'],
            [
                [
                    'guest_collision',
                    'guest_drop_goal',
                    'guest_mood_id',
                    'guest_penalty',
                    'guest_plus_minus',
                    'guest_red_card',
                    'guest_rudeness_id',
                    'guest_style_id',
                    'guest_tactic_id',
                    'guest_yellow_card',
                    'home_collision',
                    'home_drop_goal',
                    'home_mood_id',
                    'home_penalty',
                    'home_plus_minus',
                    'home_red_card',
                    'home_rudeness_id',
                    'home_style_id',
                    'home_tactic_id',
                    'home_yellow_card',
                    'weather_id',
                ],
                'integer',
                'min' => 0,
                'max' => 9
            ],
            [
                [
                    'guest_conversion',
                    'guest_possession',
                    'guest_try',
                    'home_conversion',
                    'home_possession',
                    'home_try',
                    'ticket',
                ],
                'integer',
                'min' => 0,
                'max' => 99
            ],
            [
                [
                    'guest_optimality_1',
                    'guest_optimality_2',
                    'guest_points',
                    'guest_power_percent',
                    'home_optimality_1',
                    'home_optimality_2',
                    'home_points',
                    'home_power_percent',
                ],
                'integer',
                'min' => 0,
                'max' => 999
            ],
            [['guest_forecast', 'guest_power', 'home_forecast', 'home_power'], 'integer', 'min' => 0, 'max' => 9999],
            [
                ['guest_national_id', 'home_national_id', 'stadium_capacity', 'visitor'],
                'integer',
                'min' => 0,
                'max' => 99999
            ],
            [
                [
                    'guest_plus_minus_competition',
                    'guest_plus_minus_mood',
                    'guest_plus_minus_optimality_1',
                    'guest_plus_minus_power',
                    'guest_plus_minus_score',
                    'home_plus_minus_competition',
                    'home_plus_minus_mood',
                    'home_plus_minus_optimality_1',
                    'home_plus_minus_power',
                    'home_plus_minus_score',
                ],
                'number',
                'min' => 0,
                'max' => 9
            ],
            [
                ['guest_plus_minus_optimality_2', 'guest_teamwork', 'home_plus_minus_optimality_2', 'home_teamwork'],
                'number',
                'min' => 0,
                'max' => 99
            ],
            [
                [
                    'guest_team_id',
                    'guest_user_id',
                    'home_team_id',
                    'home_user_id',
                    'played',
                    'schedule_id',
                    'stadium_id',
                ],
                'integer',
                'min' => 0,
            ],
            [['guest_mood_id'], 'exist', 'targetRelation' => 'guestMood'],
            [['guest_national_id'], 'exist', 'targetRelation' => 'guestNational'],
            [['guest_rudeness_id'], 'exist', 'targetRelation' => 'guestRudeness'],
            [['guest_style_id'], 'exist', 'targetRelation' => 'guestStyle'],
            [['guest_tactic_id'], 'exist', 'targetRelation' => 'guestTactic'],
            [['guest_team_id'], 'exist', 'targetRelation' => 'guestTeam'],
            [['guest_user_id'], 'exist', 'targetRelation' => 'guestUser'],
            [['home_mood_id'], 'exist', 'targetRelation' => 'homeMood'],
            [['home_national_id'], 'exist', 'targetRelation' => 'homeNational'],
            [['home_rudeness_id'], 'exist', 'targetRelation' => 'homeRudeness'],
            [['home_style_id'], 'exist', 'targetRelation' => 'homeStyle'],
            [['home_tactic_id'], 'exist', 'targetRelation' => 'homeTactic'],
            [['home_team_id'], 'exist', 'targetRelation' => 'homeTeam'],
            [['home_user_id'], 'exist', 'targetRelation' => 'homeUser'],
            [['schedule_id'], 'exist', 'targetRelation' => 'schedule'],
            [['stadium_id'], 'exist', 'targetRelation' => 'stadium'],
            [['weather_id'], 'exist', 'targetRelation' => 'weather'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestMood(): ActiveQuery
    {
        return $this->hasOne(Mood::class, ['id' => 'guest_mood_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'guest_national_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestRudeness(): ActiveQuery
    {
        return $this->hasOne(Rudeness::class, ['id' => 'guest_rudeness_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['id' => 'guest_style_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestTactic(): ActiveQuery
    {
        return $this->hasOne(Tactic::class, ['id' => 'guest_tactic_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'guest_team_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'guest_user_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeMood(): ActiveQuery
    {
        return $this->hasOne(Mood::class, ['id' => 'home_mood_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeNational(): ActiveQuery
    {
        return $this->hasOne(National::class, ['id' => 'home_national_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeRudeness(): ActiveQuery
    {
        return $this->hasOne(Rudeness::class, ['id' => 'home_rudeness_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['id' => 'home_style_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeTactic(): ActiveQuery
    {
        return $this->hasOne(Tactic::class, ['id' => 'home_tactic_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'home_team_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'home_user_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSchedule(): ActiveQuery
    {
        return $this->hasOne(Schedule::class, ['id' => 'schedule_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getStadium(): ActiveQuery
    {
        return $this->hasOne(Stadium::class, ['id' => 'stadium_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getWeather(): ActiveQuery
    {
        return $this->hasOne(Weather::class, ['id' => 'weather_id'])->cache();
    }
}
