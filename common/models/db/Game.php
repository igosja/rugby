<?php

// TODO refactor

namespace common\models\db;

use codeonyii\yii2validators\AtLeastValidator;
use common\components\AbstractActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Game
 * @package common\models\db
 *
 * @property int $id
 * @property int $bonus_home
 * @property bool $guest_auto
 * @property int $guest_carry
 * @property int $guest_clean_break
 * @property int $guest_collision
 * @property int $guest_conversion
 * @property int $guest_defender_beaten
 * @property int $guest_drop_goal
 * @property int $guest_forecast
 * @property int $guest_metre_gained
 * @property int $guest_mood_id
 * @property int $guest_national_id
 * @property int $guest_optimality_1
 * @property int $guest_optimality_2
 * @property int $guest_pass
 * @property int $guest_penalty_conceded
 * @property int $guest_penalty_kick
 * @property int $guest_plus_minus
 * @property float $guest_plus_minus_competition
 * @property float $guest_plus_minus_mood
 * @property float $guest_plus_minus_optimality_1
 * @property float $guest_plus_minus_optimality_2
 * @property float $guest_plus_minus_power
 * @property float $guest_plus_minus_score
 * @property int $guest_point
 * @property int $guest_possession
 * @property int $guest_power
 * @property int $guest_power_percent
 * @property int $guest_red_card
 * @property int $guest_rudeness_id
 * @property int $guest_style_id
 * @property int $guest_tackle
 * @property int $guest_tactic_id
 * @property int $guest_team_id
 * @property float $guest_teamwork
 * @property int $guest_try
 * @property int $guest_turnover_won
 * @property int $guest_user_id
 * @property int $guest_yellow_card
 * @property bool $home_auto
 * @property int $home_carry
 * @property int $home_clean_break
 * @property int $home_collision
 * @property int $home_conversion
 * @property int $home_defender_beaten
 * @property int $home_drop_goal
 * @property int $home_forecast
 * @property int $home_metre_gained
 * @property int $home_mood_id
 * @property int $home_national_id
 * @property int $home_optimality_1
 * @property int $home_optimality_2
 * @property int $home_pass
 * @property int $home_penalty_conceded
 * @property int $home_penalty_kick
 * @property int $home_plus_minus
 * @property float $home_plus_minus_competition
 * @property float $home_plus_minus_mood
 * @property float $home_plus_minus_optimality_1
 * @property float $home_plus_minus_optimality_2
 * @property float $home_plus_minus_power
 * @property float $home_plus_minus_score
 * @property int $home_point
 * @property int $home_possession
 * @property int $home_power
 * @property int $home_power_percent
 * @property int $home_red_card
 * @property int $home_rudeness_id
 * @property int $home_style_id
 * @property int $home_tackle
 * @property int $home_tactic_id
 * @property int $home_team_id
 * @property float $home_teamwork
 * @property int $home_turnover_won
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
 * @property-read Championship $championship
 * @property-read GameVote[] $gameVote
 * @property-read GameVote[] $gameVoteMinus
 * @property-read GameVote[] $gameVotePlus
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
 * @property-read Lineup[] $lineups
 * @property-read Schedule $schedule
 * @property-read Stadium $stadium
 * @property-read Weather $weather
 * @property-read WorldCup $worldCup
 */
class Game extends AbstractActiveRecord
{
    public const TICKET_PRICE_MAX = 50;
    public const TICKET_PRICE_MIN = 10;
    public const TICKET_PRICE_DEFAULT = 20;
    public const TICKET_PRICE_BASE = 9;

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
                    'guest_red_card',
                    'guest_rudeness_id',
                    'guest_style_id',
                    'guest_tactic_id',
                    'guest_yellow_card',
                    'home_collision',
                    'home_drop_goal',
                    'home_mood_id',
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
                    'guest_clean_break',
                    'guest_conversion',
                    'guest_defender_beaten',
                    'guest_penalty_conceded',
                    'guest_penalty_kick',
                    'guest_possession',
                    'guest_try',
                    'guest_turnover_won',
                    'home_clean_break',
                    'home_conversion',
                    'home_defender_beaten',
                    'home_penalty_conceded',
                    'home_penalty_kick',
                    'home_possession',
                    'home_try',
                    'home_turnover_won',
                    'ticket_price',
                ],
                'integer',
                'min' => 0,
                'max' => 99
            ],
            [
                [
                    'guest_carry',
                    'guest_metre_gained',
                    'guest_optimality_1',
                    'guest_optimality_2',
                    'guest_pass',
                    'guest_point',
                    'guest_power_percent',
                    'guest_tackle',
                    'home_carry',
                    'home_metre_gained',
                    'home_optimality_1',
                    'home_optimality_2',
                    'home_pass',
                    'home_point',
                    'home_power_percent',
                    'home_tackle',
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
                    'guest_plus_minus',
                    'guest_plus_minus_competition',
                    'guest_plus_minus_mood',
                    'guest_plus_minus_optimality_1',
                    'guest_plus_minus_power',
                    'guest_plus_minus_score',
                    'home_plus_minus',
                    'home_plus_minus_competition',
                    'home_plus_minus_mood',
                    'home_plus_minus_optimality_1',
                    'home_plus_minus_power',
                    'home_plus_minus_score',
                ],
                'number',
                'min' => -9,
                'max' => 9
            ],
            [
                ['guest_plus_minus_optimality_2', 'guest_teamwork', 'home_plus_minus_optimality_2', 'home_teamwork'],
                'number',
                'min' => -99,
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
     * @param Team|National $teamOrNational
     * @return string
     */
    public function gameHomeGuest($teamOrNational): string
    {
        if (in_array($teamOrNational->id, [$this->home_team_id, $this->home_national_id], true)) {
            $result = Yii::t('common', 'models.db.game.home');
        } else {
            $result = Yii::t('common', 'models.db.game.guest');
        }
        return $result;
    }

    /**
     * @param Team|National $teamOrNational
     * @return string
     */
    public function gamePowerPercent($teamOrNational): string
    {
        if (in_array($teamOrNational->id, [$this->home_team_id, $this->home_national_id], true)) {
            if ($this->played) {
                $result = $this->powerPercent($this->guest_power / ($this->home_power ?: 1) * 100);
            } else {
                if ($this->home_team_id === $teamOrNational->id) {
                    $result = $this->powerPercent($this->guestTeam->power_vs / ($this->homeTeam->power_vs ?: 1) * 100);
                } else {
                    $result = $this->powerPercent($this->guestNational->power_vs / ($this->homeNational->power_vs ?: 1) * 100);
                }
            }
        } else {
            if ($this->played) {
                $result = $this->powerPercent($this->home_power / ($this->guest_power ?: 1) * 100);
            } else {
                if ($this->guest_team_id === $teamOrNational->id) {
                    $result = $this->powerPercent($this->homeTeam->power_vs / ($this->guestTeam->power_vs ?: 1) * 100);
                } else {
                    $result = $this->powerPercent($this->homeNational->power_vs / ($this->guestNational->power_vs ?: 1) * 100);
                }
            }
        }
        return $result;
    }

    /**
     * @param int $percent
     * @return string
     */
    public function powerPercent(int $percent): string
    {
        return ($percent ? round($percent) : 100) . '%';
    }

    /**
     * @param Team|National $teamOrNational
     * @return string
     */
    public function gamePlusMinus($teamOrNational)
    {
        if ($this->played) {
            if (in_array($teamOrNational->id, [$this->home_team_id, $this->home_national_id], true)) {
                $result = $this->plusNecessary($this->home_plus_minus);
            } else {
                $result = $this->plusNecessary($this->guest_plus_minus);
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
    public function plusNecessary(int $value)
    {
        if ($value > 0) {
            $result = '+' . $value;
        } else {
            $result = $value;
        }
        return $result;
    }

    /**
     * @param Team|National $teamOrNational
     * @return string
     */
    public function gameAuto($teamOrNational): string
    {
        if ($this->home_auto && in_array($teamOrNational->id, [$this->home_team_id, $this->home_national_id], true)) {
            return Yii::t('common', 'models.db.game.auto');
        }

        if ($this->guest_auto && in_array($teamOrNational->id, [$this->guest_team_id, $this->guest_national_id], true)) {
            return Yii::t('common', 'models.db.game.auto');
        }
        return '';
    }

    /**
     * @param Team|National $teamOrNational
     * @return string
     */
    public function opponentLink($teamOrNational): string
    {
        if ($this->home_team_id) {
            if ($this->home_team_id === $teamOrNational->id) {
                return $this->guestTeam->getTeamImageLink();
            }

            return $this->homeTeam->getTeamImageLink();
        }

        if ($this->home_national_id === $teamOrNational->id) {
            return $this->guestNational->nationalLink();
        }

        return $this->guestNational->nationalLink();
    }

    /**
     * @param Team|National $teamOrNational
     * @return string
     */
    public function formatTeamScore($teamOrNational): string
    {
        if (in_array($teamOrNational->id, [$this->home_team_id, $this->home_national_id], true)) {
            return $this->formatScore();
        }

        return $this->formatScore('guest');
    }

    /**
     * @param string $team
     * @return string
     */
    public function cssMood(string $team): string
    {
        $classLoose = 'font-red';
        $classWin = 'font-green';

        $mood = $team . '_mood_id';
        $mood = $this->$mood;

        if (Mood::SUPER === $mood) {
            return $classWin;
        }
        if (Mood::REST === $mood) {
            return $classLoose;
        }
        return '';
    }

    /**
     * @param string $team
     * @return string
     */
    public function cssStyle(string $team): string
    {
        $classLoose = 'font-red';
        $classWin = 'font-green';

        if ('home' === $team) {
            $opponent = 'guest';
        } else {
            $opponent = 'home';
        }

        $style1 = $team . '_style_id';
        $style1 = $this->$style1;
        $style2 = $opponent . '_style_id';
        $style2 = $this->$style2;

        if (Style::MAN_10 === $style1 && Style::MAN_15 === $style2) {
            return $classWin;
        }
        if (Style::DOWN_THE_MIDDLE === $style1 && Style::MAN_10 === $style2) {
            return $classWin;
        }
        if (Style::CHAMPAGNE === $style1 && Style::DOWN_THE_MIDDLE === $style2) {
            return $classWin;
        }
        if (Style::MAN_15 === $style1 && Style::CHAMPAGNE === $style2) {
            return $classWin;
        }
        if (Style::MAN_15 === $style1 && Style::MAN_10 === $style2) {
            return $classLoose;
        }
        if (Style::MAN_10 === $style1 && Style::DOWN_THE_MIDDLE === $style2) {
            return $classLoose;
        }
        if (Style::DOWN_THE_MIDDLE === $style1 && Style::CHAMPAGNE === $style2) {
            return $classLoose;
        }
        if (Style::CHAMPAGNE === $style1 && Style::MAN_15 === $style2) {
            return $classLoose;
        }
        return '';
    }

    /**
     * @return string
     */
    public function rating(): string
    {
        $returnArray = [
            '<span class="font-green">' . count($this->gameVotePlus) . '</span>',
            '<span class="font-red">' . count($this->gameVoteMinus) . '</span>',
        ];

        return implode(' | ', $returnArray);
    }

    /**
     * @return string
     */
    public function tournamentLink(): string
    {
        if (TournamentType::NATIONAL === $this->schedule->tournament_type_id) {
            $result = Html::a(
                $this->schedule->tournamentType->name . ', ' . $this->schedule->stage->name,
                [
                    'world-championship/index',
                    'seasonId' => $this->schedule->season_id,
                    'stageId' => $this->schedule->stage_id,
                    'divisionId' => $this->worldCup->division_id,
                    'nationalTypeId' => $this->homeNational->national_type_id,
                ]
            );
        } elseif (TournamentType::LEAGUE === $this->schedule->tournament_type_id) {
            if ($this->schedule->stage_id <= Stage::QUALIFY_3) {
                $round = 'qualification';
            } elseif ($this->schedule->stage_id <= Stage::TOUR_LEAGUE_6) {
                $round = 'table';
            } else {
                $round = 'playoff';
            }

            $result = Html::a(
                $this->schedule->tournamentType->name . ', ' . $this->schedule->stage->name,
                [
                    'champions-league/' . $round,
                    'seasonId' => $this->schedule->season_id,
                ]
            );
        } elseif (TournamentType::CHAMPIONSHIP === $this->schedule->tournament_type_id) {
            if ($this->schedule->stage_id <= Stage::TOUR_30) {
                $round = 'table';
            } else {
                $round = 'playoff';
            }

            $result = Html::a(
                $this->schedule->tournamentType->name . ', ' . $this->schedule->stage->name,
                [
                    'championship/' . $round,
                    'seasonId' => $this->schedule->season_id,
                    'divisionId' => $this->championship->division_id,
                    'federation_id' => $this->championship->federation_id,
                    'stageId' => $this->schedule->stage_id,
                ]
            );
        } elseif (TournamentType::CONFERENCE === $this->schedule->tournament_type_id) {
            $result = Html::a(
                $this->schedule->tournamentType->name . ', ' . $this->schedule->stage->name,
                [
                    'conference/table',
                    'seasonId' => $this->schedule->season_id,
                ]
            );
        } elseif (TournamentType::OFF_SEASON === $this->schedule->tournament_type_id) {
            $result = Html::a(
                $this->schedule->tournamentType->name . ', ' . $this->schedule->stage->name,
                [
                    'off-season/table',
                    'seasonId' => $this->schedule->season_id,
                ]
            );
        } else {
            $result = $this->schedule->tournamentType->name;
        }

        return $result;
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
            $team = $this->homeTeam;
            $national = $this->homeNational;
        } else {
            $team = $this->guestTeam;
            $national = $this->guestNational;
        }
        if ($team->id) {
            $name = $team->name;

            if (true === $full) {
                $name .= ' ' . Html::tag(
                        'span',
                        '(' . $team->stadium->city->name . ', ' . $team->stadium->city->country->name . ')',
                        ['class' => 'hidden-xs']
                    );
            }

            if (true === $link) {
                return Html::a($name, ['team/view', 'id' => $team->id]);
            }

            return $name;
        }

        if ($national->id) {
            $name = $national->federation->country->name;

            if ($full) {
                $name .= ' ' . Html::tag(
                        'span',
                        '(' . $national->nationalType->name . ')',
                        ['class' => 'hidden-xs']
                    );
            }

            if (true === $link) {
                return Html::a($name, ['national/view', 'id' => $national->id]);
            }

            return $name;
        }

        return '';
    }

    /**
     * @param string $side
     * @return string
     */
    public function formatAuto(string $side = 'home'): string
    {
        if ('home' === $side) {
            $auto = $this->home_auto;
        } else {
            $auto = $this->guest_auto;
        }
        if ($auto) {
            return '*';
        }
        return '';
    }

    /**
     * @param string $first
     * @return string
     */
    public function formatScore($first = 'home'): string
    {
        if ($this->played) {
            if ('home' === $first) {
                return $this->home_point . ':' . $this->guest_point;
            }

            return $this->guest_point . ':' . $this->home_point;
        }

        return '?:?';
    }

    /**
     * @return ActiveQuery
     */
    public function getChampionship(): ActiveQuery
    {
        return $this
            ->hasOne(Championship::class, ['team_id' => 'home_team_id'])
            ->andWhere(['season_id' => $this->schedule->season_id]);
    }

    /**
     * @return ActiveQuery
     */
    public function getGameVote(): ActiveQuery
    {
        return $this->hasMany(GameVote::class, ['game_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGameVoteMinus(): ActiveQuery
    {
        return $this->getGameVote()->andWhere(['<', 'rating', 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getGameVotePlus(): ActiveQuery
    {
        return $this->getGameVote()->andWhere(['>', 'rating', 0]);
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
        return $this->hasOne(National::class, ['id' => 'guest_national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestRudeness(): ActiveQuery
    {
        return $this->hasOne(Rudeness::class, ['id' => 'guest_rudeness_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['id' => 'guest_style_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestTactic(): ActiveQuery
    {
        return $this->hasOne(Tactic::class, ['id' => 'guest_tactic_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'guest_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGuestUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'guest_user_id']);
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
        return $this->hasOne(National::class, ['id' => 'home_national_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeRudeness(): ActiveQuery
    {
        return $this->hasOne(Rudeness::class, ['id' => 'home_rudeness_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeStyle(): ActiveQuery
    {
        return $this->hasOne(Style::class, ['id' => 'home_style_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeTactic(): ActiveQuery
    {
        return $this->hasOne(Tactic::class, ['id' => 'home_tactic_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'home_team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHomeUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'home_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLineups(): ActiveQuery
    {
        return $this->hasMany(Lineup::class, ['game_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSchedule(): ActiveQuery
    {
        return $this->hasOne(Schedule::class, ['id' => 'schedule_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStadium(): ActiveQuery
    {
        return $this->hasOne(Stadium::class, ['id' => 'stadium_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getWeather(): ActiveQuery
    {
        return $this->hasOne(Weather::class, ['id' => 'weather_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getWorldCup(): ActiveQuery
    {
        return $this
            ->hasOne(WorldCup::class, ['national_id' => 'home_national_id'])
            ->andWhere(['season_id' => $this->schedule->season_id]);
    }
}
