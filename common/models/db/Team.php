<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Exception;
use frontend\components\AbstractController;
use frontend\models\queries\GameQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Team
 * @package common\models\db
 *
 * @property int $team_id
 * @property float $team_age
 * @property int $team_attitude_national
 * @property int $team_attitude_president
 * @property int $team_attitude_u19
 * @property int $team_attitude_u21
 * @property int $team_auto
 * @property int $team_base_id
 * @property int $team_base_medical_id
 * @property int $team_base_physical_id
 * @property int $team_base_school_id
 * @property int $team_base_scout_id
 * @property int $team_base_training_id
 * @property int $team_finance
 * @property int $team_friendly_status_id
 * @property int $team_free_base
 * @property int $team_mood_rest
 * @property int $team_mood_super
 * @property string $team_name
 * @property int $team_news_id
 * @property int $team_player
 * @property int $team_power_c_15
 * @property int $team_power_c_19
 * @property int $team_power_c_24
 * @property int $team_power_s_15
 * @property int $team_power_s_19
 * @property int $team_power_s_24
 * @property int $team_power_v
 * @property int $team_power_vs
 * @property int $team_price_base
 * @property int $team_price_player
 * @property int $team_price_stadium
 * @property int $team_price_total
 * @property int $team_salary
 * @property int $team_stadium_id
 * @property int $team_user_id
 * @property int $team_vice_id
 * @property int $team_visitor
 *
 * @property Base $base
 * @property BaseMedical $baseMedical
 * @property BasePhysical $basePhysical
 * @property BaseSchool $baseSchool
 * @property BaseScout $baseScout
 * @property BaseTraining $baseTraining
 * @property BuildingBase $buildingBase
 * @property BuildingStadium $buildingStadium
 * @property Championship $championship
 * @property Conference $conference
 * @property User $manager
 * @property OffSeason $offSeason
 * @property Player[] $players
 * @property RatingTeam $ratingTeam
 * @property Stadium $stadium
 * @property TeamRequest[] $teamRequests
 * @property User $vice
 */
class Team extends AbstractActiveRecord
{
    public const START_MONEY = 10000000;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%team}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                [
                    'team_id',
                    'team_attitude_national',
                    'team_attitude_president',
                    'team_attitude_u19',
                    'team_attitude_u21',
                    'team_auto',
                    'team_base_id',
                    'team_base_medical_id',
                    'team_base_physical_id',
                    'team_base_school_id',
                    'team_base_scout_id',
                    'team_base_training_id',
                    'team_finance',
                    'team_friendly_status_id',
                    'team_free_base',
                    'team_mood_rest',
                    'team_mood_super',
                    'team_news_id',
                    'team_player',
                    'team_power_c_15',
                    'team_power_c_19',
                    'team_power_c_24',
                    'team_power_s_15',
                    'team_power_s_19',
                    'team_power_s_24',
                    'team_power_v',
                    'team_power_vs',
                    'team_price_base',
                    'team_price_player',
                    'team_price_stadium',
                    'team_price_total',
                    'team_salary',
                    'team_stadium_id',
                    'team_user_id',
                    'team_vice_id',
                    'team_visitor',
                ],
                'integer'
            ],
            [['team_age'], 'number'],
            [['team_name'], 'string', 'max' => 255],
            [['team_name'], 'trim'],
        ];
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->team_attitude_national = Attitude::NEUTRAL;
            $this->team_attitude_president = Attitude::NEUTRAL;
            $this->team_attitude_u19 = Attitude::NEUTRAL;
            $this->team_attitude_u21 = Attitude::NEUTRAL;
            $this->team_base_id = Base::START_LEVEL;
            $this->team_base_medical_id = BaseMedical::START_LEVEL;
            $this->team_base_physical_id = BasePhysical::START_LEVEL;
            $this->team_base_school_id = BaseSchool::START_LEVEL;
            $this->team_base_scout_id = BaseScout::START_LEVEL;
            $this->team_base_training_id = BaseTraining::START_LEVEL;
            $this->team_finance = Team::START_MONEY;
            $this->team_free_base = Base::FREE_SLOTS;
            $this->team_mood_rest = Mood::START_REST;
            $this->team_mood_super = Mood::START_SUPER;
            $this->team_player = Player::START_NUMBER_OF_PLAYERS;
        }
        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes): bool
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            History::log([
                'history_history_text_id' => HistoryText::TEAM_REGISTER,
                'history_team_id' => $this->team_id,
            ]);
            Finance::log([
                'finance_finance_text_id' => FinanceText::TEAM_RE_REGISTER,
                'finance_team_id' => $this->team_id,
                'finance_value' => Team::START_MONEY,
                'finance_value_after' => Team::START_MONEY,
                'finance_value_before' => 0,
            ]);
            $this->createPlayers();
            $this->createLeaguePlayers();
            $this->updatePower();
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function createPlayers(): bool
    {
        $position = [
            Position::POS_01,
            Position::POS_01,
            Position::POS_02,
            Position::POS_02,
            Position::POS_03,
            Position::POS_03,
            Position::POS_04,
            Position::POS_04,
            Position::POS_05,
            Position::POS_05,
            Position::POS_06,
            Position::POS_06,
            Position::POS_07,
            Position::POS_07,
            Position::POS_08,
            Position::POS_08,
            Position::POS_09,
            Position::POS_09,
            Position::POS_10,
            Position::POS_10,
            Position::POS_11,
            Position::POS_11,
            Position::POS_12,
            Position::POS_12,
            Position::POS_13,
            Position::POS_13,
            Position::POS_14,
            Position::POS_14,
            Position::POS_15,
            Position::POS_15,
        ];

        shuffle($position);

        for ($i = 0, $countPosition = count($position); $i < $countPosition; $i++) {
            $this->createOnePlayer($position[$i], $i, $this->team_id);
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function createLeaguePlayers(): bool
    {
        $position = [
            Position::POS_01,
            Position::POS_02,
            Position::POS_03,
            Position::POS_04,
            Position::POS_05,
            Position::POS_06,
            Position::POS_07,
            Position::POS_08,
            Position::POS_09,
            Position::POS_10,
            Position::POS_11,
            Position::POS_12,
            Position::POS_13,
            Position::POS_14,
            Position::POS_15,
        ];

        shuffle($position);

        for ($i = 0, $countPosition = count($position); $i < $countPosition; $i++) {
            $this->createOnePlayer($position[$i], $i);
        }

        return true;
    }

    /**
     * @param int $position
     * @param int $i
     * @param int $teamId
     * @throws Exception
     */
    private function createOnePlayer(int $position, int $i, int $teamId = 0)
    {
        $age = 17 + $i;

        if ($age > 35) {
            $age = $age - 17;
        }

        $player = new Player();
        $player->player_age = $age;
        $player->player_country_id = $this->stadium->city->city_country_id;
        $player->player_position_id = $position;
        $player->player_team_id = $teamId;
        $player->save();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function updatePower(): bool
    {
        $player15 = Player::find()
            ->select(['player_id'])
            ->where(['player_team_id' => $this->team_id])
            ->orderBy(['player_power_nominal' => SORT_DESC])
            ->limit(15)
            ->column();
        $player19 = Player::find()
            ->select(['player_id'])
            ->where(['player_team_id' => $this->team_id])
            ->orderBy(['player_power_nominal' => SORT_DESC])
            ->limit(19)
            ->column();
        $player24 = Player::find()
            ->select(['player_id'])
            ->where(['player_team_id' => $this->team_id])
            ->orderBy(['player_power_nominal' => SORT_DESC])
            ->limit(24)
            ->column();
        $power_c_15 = Player::find()->where(['player_id' => $player15])->sum('player_power_nominal');
        $power_c_19 = Player::find()->where(['player_id' => $player19])->sum('player_power_nominal');
        $power_c_24 = Player::find()->where(['player_id' => $player24])->sum('player_power_nominal');
        $power_s_15 = Player::find()->where(['player_id' => $player15])->sum('player_power_nominal_s');
        $power_s_19 = Player::find()->where(['player_id' => $player19])->sum('player_power_nominal_s');
        $power_s_24 = Player::find()->where(['player_id' => $player24])->sum('player_power_nominal_s');
        $power_v = round(($power_c_15 + $power_c_19 + $power_c_24) / 58 * 15);
        $power_vs = round(($power_s_15 + $power_s_19 + $power_s_24) / 58 * 15);

        $this->team_power_c_15 = $power_c_15;
        $this->team_power_c_19 = $power_c_19;
        $this->team_power_c_24 = $power_c_24;
        $this->team_power_s_15 = $power_s_15;
        $this->team_power_s_19 = $power_s_19;
        $this->team_power_s_24 = $power_s_24;
        $this->team_power_v = $power_v;
        $this->team_power_vs = $power_vs;
        $this->save(true, [
            'team_power_c_15',
            'team_power_c_19',
            'team_power_c_24',
            'team_power_s_15',
            'team_power_s_19',
            'team_power_s_24',
            'team_power_v',
            'team_power_vs',
        ]);

        return true;
    }

    /**
     * @return string
     */
    public function offSeason(): string
    {
        $result = '-';
        if ($this->offSeason) {
            $result = Html::a(
                $this->offSeason->off_season_place . ' место',
                ['off-season/table']
            );
        }
        return $result;
    }

    /**
     * @return string
     */
    public function division(): string
    {
        if ($this->championship) {
            $result = Html::a(
                $this->championship->country->country_name . ', ' .
                $this->championship->division->division_name . ', ' .
                $this->championship->championship_place . ' ' .
                'место',
                [
                    'championship/index',
                    'countryId' => $this->championship->country->country_id,
                    'divisionId' => $this->championship->division->division_id,
                ]
            );
        } else {
            $result = Html::a(
                'Конференция, ' . $this->conference->conference_place . ' место',
                ['conference/table']
            );
        }
        return $result;
    }

    /**
     * @return string
     */
    public function logo(): string
    {
        $result = 'Добавить<br/>эмблему';
        if (file_exists(Yii::getAlias('@webroot') . '/img/team/125/' . $this->team_id . '.png')) {
            $result = Html::img(
                '/img/team/125/' . $this->team_id . '.png?v='
                . filemtime(Yii::getAlias('@webroot') . '/img/team/125/' . $this->team_id . '.png'),
                [
                    'alt' => $this->team_name,
                    'class' => 'team-logo',
                    'title' => $this->team_name,
                ]
            );
        }
        return $result;
    }

    /**
     * @return int
     */
    public function baseUsed(): int
    {
        return $this->baseMedical->base_medical_level
            + $this->basePhysical->base_physical_level
            + $this->baseSchool->base_school_level
            + $this->baseScout->base_scout_level
            + $this->baseTraining->base_training_level;
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        return $this->team_name
            . ' (' . $this->stadium->city->city_name
            . ', ' . $this->stadium->city->country->country_name
            . ')';
    }

    /**
     * @param string $type
     * @param bool $short
     * @return string
     */
    public function teamLink(string $type = 'string', bool $short = false): string
    {
        if ('img' == $type) {
            return $this->stadium->city->country->countryImage()
                . ' '
                . Html::a(
                    $this->team_name
                    . ' <span class="hidden-xs">('
                    . $this->stadium->city->city_name
                    . ')</span>',
                    ['team/view', 'id' => $this->team_id]
                );
        } else {
            if ($short) {
                return Html::a(
                    $this->team_name
                    . ' <span class="hidden-xs">('
                    . $this->stadium->city->city_name
                    . ')</span>',
                    ['team/view', 'id' => $this->team_id]
                );
            } else {
                return Html::a(
                    $this->team_name
                    . ' <span class="hidden-xs">('
                    . $this->stadium->city->city_name
                    . ', '
                    . $this->stadium->city->country->country_name
                    . ')</span>',
                    ['team/view', 'id' => $this->team_id]
                );
            }
        }
    }

    /**
     * @return bool
     */
    public function myTeam(): bool
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;

        if (!$controller->myTeam) {
            return false;
        }

        if ($controller->myTeam->team_id != $this->team_id) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function myTeamOrVice(): bool
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;

        if (!$controller->myTeamOrVice) {
            return false;
        }

        if ($controller->myTeamOrVice->team_id != $this->team_id) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function iconFreeTeam(): string
    {
        $result = ' ';
        if (!$this->team_user_id) {
            $result = Html::tag('i', '', ['class' => ['fa', 'fa-flag-o'], 'title' => 'Свободная команда']) . $result;
        }
        return $result;
    }

    /**
     * @return string
     */
    public function rosterPhrase(): string
    {
        $data = [
            'Уезжая надолго и без интернета не забудьте поставить статус ' . Html::a('в отпуске', ['user/holiday']),
            Html::a('Пригласите друзей', ['user/referral']) . ' в Лигу и получите вознаграждение',
            'Если у вас есть вопросы, задайте их специалистам ' . Html::a('тех.поддержки', ['support/index']) . ' Лиги',
            'Можно достичь высоких результатов, не нарушая правил',
            'Играйте честно - так интереснее выигрывать',
        ];
        return $data[array_rand($data)];
    }

    /**
     * @return Game[]
     */
    public function latestGame(): array
    {
        return array_reverse(GameQuery::getLastThreeGames($this->team_id));
    }

    /**
     * @return Game[]
     */
    public function nearestGame(): array
    {
        return GameQuery::getNearestTwoGames($this->team_id);
    }

    /**
     * @return ForumMessage[]
     */
    public function forumLastArray(): array
    {
        return ForumMessage::find()
            ->select([
                '*',
                'forum_message_id' => 'MAX(forum_message_id)',
                'forum_message_date' => 'MAX(forum_message_date)',
            ])
            ->joinWith(['forumTheme.forumGroup'])
            ->where([
                'forum_group.forum_group_country_id' => $this->stadium->city->country->country_id
            ])
            ->groupBy(['forum_message_forum_theme_id'])
            ->orderBy(['forum_message_id' => SORT_DESC])
            ->limit(5)
            ->all();
    }

    /**
     * @return bool
     */
    public function canViceLeave(): bool
    {
        /**
         * @var AbstractController $controller
         */
        $controller = Yii::$app->controller;
        if ($controller->user && $controller->user->user_id == $this->team_vice_id) {
            return true;
        }
        return false;
    }

    /**
     * @return ActiveQuery
     */
    public function getBase(): ActiveQuery
    {
        return $this->hasOne(Base::class, ['base_id' => 'team_base_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseMedical(): ActiveQuery
    {
        return $this->hasOne(BaseMedical::class, ['base_medical_id' => 'team_base_medical_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBasePhysical(): ActiveQuery
    {
        return $this->hasOne(BasePhysical::class, ['base_physical_id' => 'team_base_physical_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseSchool(): ActiveQuery
    {
        return $this->hasOne(BaseSchool::class, ['base_school_id' => 'team_base_school_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseScout(): ActiveQuery
    {
        return $this->hasOne(BaseScout::class, ['base_scout_id' => 'team_base_scout_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseTraining(): ActiveQuery
    {
        return $this->hasOne(BaseTraining::class, ['base_training_id' => 'team_base_training_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBuildingBase(): ActiveQuery
    {
        return $this
            ->hasOne(BuildingBase::class, ['building_base_team_id' => 'team_id'])
            ->andWhere(['building_base_ready' => 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getBuildingStadium(): ActiveQuery
    {
        return $this
            ->hasOne(BuildingStadium::class, ['building_stadium_team_id' => 'team_id'])
            ->andWhere(['building_stadium_ready' => 0]);
    }

    /**
     * @return ActiveQuery
     */
    public function getChampionship(): ActiveQuery
    {
        return $this
            ->hasOne(Championship::class, ['championship_team_id' => 'team_id'])
            ->andWhere(['championship_season_id' => Season::getCurrentSeason()])
            ->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getConference(): ActiveQuery
    {
        return $this
            ->hasOne(Conference::class, ['conference_team_id' => 'team_id'])
            ->andWhere(['conference_season_id' => Season::getCurrentSeason()])
            ->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getManager(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'team_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOffSeason(): ActiveQuery
    {
        return $this
            ->hasOne(OffSeason::class, ['off_season_team_id' => 'team_id'])
            ->andWhere(['off_season_season_id' => Season::getCurrentSeason()])
            ->cache();
    }

    /**
     * @return ActiveQuery
     */
    public function getPlayers(): ActiveQuery
    {
        return $this->hasMany(Player::class, ['player_team_id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRatingTeam(): ActiveQuery
    {
        return $this->hasOne(RatingTeam::class, ['rating_team_team_id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStadium(): ActiveQuery
    {
        return $this->hasOne(Stadium::class, ['stadium_id' => 'team_stadium_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeamRequests(): ActiveQuery
    {
        return $this->hasMany(TeamRequest::class, ['team_request_team_id' => 'team_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVice(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'team_vice_id']);
    }
}
