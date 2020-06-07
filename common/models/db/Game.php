<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Game
 * @package common\models\db
 *
 * @property int $game_id
 * @property int $game_bonus_home
 * @property int $game_guest_auto
 * @property int $game_guest_collision
 * @property int $game_guest_conversion
 * @property int $game_guest_drop_goal
 * @property int $game_guest_forecast
 * @property int $game_guest_mood_id
 * @property int $game_guest_national_id
 * @property int $game_guest_optimality_1
 * @property int $game_guest_optimality_2
 * @property int $game_guest_penalty
 * @property int $game_guest_plus_minus
 * @property float $game_guest_plus_minus_competition
 * @property float $game_guest_plus_minus_mood
 * @property float $game_guest_plus_minus_optimality_1
 * @property float $game_guest_plus_minus_optimality_2
 * @property float $game_guest_plus_minus_power
 * @property float $game_guest_plus_minus_score
 * @property int $game_guest_points
 * @property int $game_guest_possession
 * @property int $game_guest_power
 * @property int $game_guest_power_percent
 * @property int $game_guest_red_card
 * @property int $game_guest_rudeness_id
 * @property int $game_guest_style_id
 * @property int $game_guest_tactic_id
 * @property int $game_guest_team_id
 * @property float $game_guest_teamwork
 * @property int $game_guest_try
 * @property int $game_guest_user_id
 * @property int $game_guest_yellow_card
 * @property int $game_home_auto
 * @property int $game_home_collision
 * @property int $game_home_conversion
 * @property int $game_home_drop_goal
 * @property int $game_home_forecast
 * @property int $game_home_mood_id
 * @property int $game_home_national_id
 * @property int $game_home_optimality_1
 * @property int $game_home_optimality_2
 * @property int $game_home_penalty
 * @property int $game_home_plus_minus
 * @property float $game_home_plus_minus_competition
 * @property float $game_home_plus_minus_mood
 * @property float $game_home_plus_minus_optimality_1
 * @property float $game_home_plus_minus_optimality_2
 * @property float $game_home_plus_minus_power
 * @property float $game_home_plus_minus_score
 * @property int $game_home_points
 * @property int $game_home_possession
 * @property int $game_home_power
 * @property int $game_home_power_percent
 * @property int $game_home_red_card
 * @property int $game_home_rudeness_id
 * @property int $game_home_style_id
 * @property int $game_home_tactic_id
 * @property int $game_home_team_id
 * @property float $game_home_teamwork
 * @property int $game_home_try
 * @property int $game_home_user_id
 * @property int $game_home_yellow_card
 * @property int $game_played
 * @property int $game_ticket
 * @property int $game_schedule_id
 * @property int $game_stadium_capacity
 * @property int $game_stadium_id
 * @property int $game_visitor
 * @property int $game_weather_id
 *
 * @property National $nationalGuest
 * @property National $nationalHome
 * @property Schedule $schedule
 * @property Team $teamGuest
 * @property Team $teamHome
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
     * @param string $first
     * @return string
     */
    public function formatScore($first = 'home'): string
    {
        if ($this->game_played) {
            if ('home' === $first) {
                return $this->game_home_points . ':' . $this->game_guest_points;
            }
            return $this->game_guest_points . ':' . $this->game_home_points;
        }
        return '?:?';
    }

    /**
     * @param string $side
     * @param bool $full
     * @param bool $link
     * @return string
     */
    public function teamOrNationalLink(string $side = 'home', bool $full = true, bool $link = true): string
    {
        if ('home' === $side) {
            $team = $this->teamHome;
            $national = $this->nationalHome;
        } else {
            $team = $this->teamGuest;
            $national = $this->nationalGuest;
        }
        if ($team->team_id) {
            $name = $team->team_name;

            if ($full) {
                $name .= ' ' . Html::tag(
                        'span',
                        '(' . $team->stadium->city->city_name . ', ' . $team->stadium->city->country->country_name . ')',
                        ['class' => 'hidden-xs']
                    );
            }

            if ($link) {
                return Html::a($name, ['team/view', 'id' => $team->team_id]);
            }
            return $name;
        }

        if ($national->national_id) {
            $name = $national->country->country_name;

            if ($full) {
                $name .= ' ' . Html::tag(
                        'span',
                        '(' . $national->nationalType->national_type_name . ')',
                        ['class' => 'hidden-xs']
                    );
            }

            if ($link) {
                return Html::a($name, ['national/view', 'id' => $national->national_id]);
            }

            return $name;
        }

        return '';
    }

    /**
     * @param int $teamOrNationalId
     * @return string
     */
    public function gameHomeGuest(int $teamOrNationalId): string
    {
        if (in_array($teamOrNationalId, [$this->game_home_team_id, $this->game_home_national_id], true)) {
            return 'Д';
        }
        return 'Г';
    }

    /**
     * @param int $teamOrNationalId
     * @return string
     */
    public function gamePowerPercent(int $teamOrNationalId): string
    {
        if (in_array($teamOrNationalId, [$this->game_home_team_id, $this->game_home_national_id], true)) {
            if ($this->game_played) {
                return self::powerPercent($this->game_guest_power / ($this->game_home_power ?: 1) * 100);
            }
            return self::powerPercent($this->teamGuest->team_power_vs / ($this->teamHome->team_power_vs ?: 1) * 100);
        }

        if ($this->game_played) {
            return self::powerPercent($this->game_home_power / ($this->game_guest_power ?: 1) * 100);
        }
        return self::powerPercent($this->teamHome->team_power_vs / ($this->teamGuest->team_power_vs ?: 1) * 100);
    }

    /**
     * @param int $percent
     * @return string
     */
    public static function powerPercent(int $percent): string
    {
        return ($percent ? round($percent) : 100) . '%';
    }

    /**
     * @param int $teamOrNationalId
     * @return string
     */
    public function opponentLink(int $teamOrNationalId): string
    {
        if ($this->game_home_team_id) {
            if ($this->game_home_team_id === $teamOrNationalId) {
                return $this->teamGuest->teamLink('img');
            }
            return $this->teamHome->teamLink('img');
        }

        if ($this->game_home_national_id === $teamOrNationalId) {
            return $this->nationalGuest->nationalLink(true);
        }
        return $this->nationalHome->nationalLink(true);
    }

    /**
     * @param string $side
     * @return string
     */
    public function formatAuto(string $side = 'home'): string
    {
        if ('home' === $side) {
            $auto = $this->game_home_auto;
        } else {
            $auto = $this->game_guest_auto;
        }

        if ($auto) {
            return '*';
        }
        return '';
    }

    /**
     * @param int $teamOrNationalId
     * @return string
     */
    public function gameAuto(int $teamOrNationalId): string
    {
        if ($this->game_home_auto && in_array($teamOrNationalId, [$this->game_home_team_id, $this->game_home_national_id], true)) {
            return 'A';
        }
        if ($this->game_guest_auto && in_array($teamOrNationalId, [$this->game_guest_team_id, $this->game_guest_national_id], true)) {
            return 'A';
        }
        return '';
    }

    /**
     * @param int $teamOrNationalId
     * @return string
     */
    public function formatTeamScore(int $teamOrNationalId): string
    {
        if (in_array($teamOrNationalId, [$this->game_home_team_id, $this->game_home_national_id], true)) {
            return $this->formatScore();
        }
        return $this->formatScore('guest');
    }

    /**
     * @param int $teamId
     * @return string
     */
    public function gamePlusMinus(int $teamId): string
    {
        if ($this->game_played) {
            if ($this->game_home_team_id === $teamId) {
                $result = $this->plusNecessary($this->game_home_plus_minus);
            } else {
                $result = $this->plusNecessary($this->game_guest_plus_minus);
            }
        } else {
            $result = '';
        }
        return $result;
    }

    /**
     * @param int $value
     * @return string
     */
    public function plusNecessary(int $value): string
    {
        if ($value > 0) {
            $result = '+' . $value;
        } else {
            $result = $value;
        }
        return (string)$result;
    }

    /**
     * @return ActiveQuery
     */
    public function getNationalGuest(): ActiveQuery
    {
        return $this->hasOne(National::class, ['national_id' => 'game_guest_national_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getNationalHome(): ActiveQuery
    {
        return $this->hasOne(National::class, ['national_id' => 'game_home_national_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getSchedule(): ActiveQuery
    {
        return $this->hasOne(Schedule::class, ['schedule_id' => 'game_schedule_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamGuest(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'game_guest_team_id'])->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamHome(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_id' => 'game_home_team_id'])->cache();
    }
}
