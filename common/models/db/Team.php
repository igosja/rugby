<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\ErrorHelper;
use Exception;
use frontend\controllers\AbstractController;
use rmrevin\yii\fontawesome\FAS;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class Team
 * @package common\models\db
 *
 * @property int $id
 * @property int $auto_number
 * @property int $base_id
 * @property int $base_medical_id
 * @property int $base_physical_id
 * @property int $base_school_id
 * @property int $base_scout_id
 * @property int $base_training_id
 * @property int $federation_news_id
 * @property int $finance
 * @property int $friendly_status_id
 * @property int $free_base_number
 * @property int $mood_rest
 * @property int $mood_super
 * @property string $name
 * @property int $national_attitude_id
 * @property float $player_average_age
 * @property int $player_number
 * @property int $power_c_15
 * @property int $power_c_19
 * @property int $power_c_24
 * @property int $power_s_15
 * @property int $power_s_19
 * @property int $power_s_24
 * @property int $power_v
 * @property int $power_vs
 * @property int $president_attitude_id
 * @property int $price_base
 * @property int $price_player
 * @property int $price_stadium
 * @property int $price_total
 * @property int $salary
 * @property int $stadium_id
 * @property int $u19_attitude_id
 * @property int $u21_attitude_id
 * @property int $user_id
 * @property int $vice_user_id
 * @property int $visitor
 *
 * @property-read Base $base
 * @property-read BaseMedical $baseMedical
 * @property-read BasePhysical $basePhysical
 * @property-read BaseSchool $baseSchool
 * @property-read BaseScout $baseScout
 * @property-read BaseTraining $baseTraining
 * @property-read BuildingBase $buildingBase
 * @property-read BuildingStadium $buildingStadium
 * @property-read News $federationNews
 * @property-read Championship $championship
 * @property-read Conference $conference
 * @property-read FriendlyStatus $friendlyStatus
 * @property-read OffSeason $offSeason
 * @property-read Attitude $nationalAttitude
 * @property-read Attitude $presidentAttitude
 * @property-read RatingTeam $ratingTeam
 * @property-read Stadium $stadium
 * @property-read TeamRequest[] $teamRequests
 * @property-read Attitude $u19Attitude
 * @property-read Attitude $u21Attitude
 * @property-read User $user
 * @property-read User $viceUser
 */
class Team extends AbstractActiveRecord
{
    public const MAX_AUTO_GAMES = 5;
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
            [['name', 'stadium_id'], 'required'],
            [
                [
                    'auto_number',
                    'friendly_status_id',
                    'free_base_number',
                    'mood_rest',
                    'mood_super',
                    'national_attitude_id',
                    'president_attitude_id',
                    'u19_attitude_id',
                    'u21_attitude_id',
                ],
                'integer',
                'min' => 0,
                'max' => 9
            ],
            [
                [
                    'base_id',
                    'base_medical_id',
                    'base_physical_id',
                    'base_school_id',
                    'base_scout_id',
                    'base_training_id'
                ],
                'integer',
                'min' => 0,
                'max' => 99
            ],
            [['player_average_age'], 'number', 'min' => 0, 'max' => 99],
            [['player_number', 'visitor'], 'integer', 'min' => 0, 'max' => 999],
            [
                [
                    'power_c_15',
                    'power_c_19',
                    'power_c_24',
                    'power_s_15',
                    'power_s_19',
                    'power_s_24',
                    'power_v',
                    'power_vs',
                ],
                'integer',
                'min' => 0,
                'max' => 99999
            ],
            [
                [
                    'federation_news_id',
                    'finance',
                    'price_base',
                    'price_player',
                    'price_stadium',
                    'price_total',
                    'salary',
                    'stadium_id',
                    'user_id',
                    'vice_user_id',
                ],
                'number',
                'min' => 0
            ],
            [['name'], 'trim'],
            [['name'], 'string'],
            [['base_id'], 'exist', 'targetRelation' => 'base'],
            [['base_medical_id'], 'exist', 'targetRelation' => 'baseMedical'],
            [['base_physical_id'], 'exist', 'targetRelation' => 'basePhysical'],
            [['base_school_id'], 'exist', 'targetRelation' => 'baseSchool'],
            [['base_scout_id'], 'exist', 'targetRelation' => 'baseScout'],
            [['base_training_id'], 'exist', 'targetRelation' => 'baseTraining'],
            [['federation_news_id'], 'exist', 'targetRelation' => 'federationNews'],
            [['friendly_status_id'], 'exist', 'targetRelation' => 'friendlyStatus'],
            [['national_attitude_id'], 'exist', 'targetRelation' => 'nationalAttitude'],
            [['president_attitude_id'], 'exist', 'targetRelation' => 'presidentAttitude'],
            [['stadium_id'], 'exist', 'targetRelation' => 'stadium'],
            [['u19_attitude_id'], 'exist', 'targetRelation' => 'u19Attitude'],
            [['u21_attitude_id'], 'exist', 'targetRelation' => 'u21Attitude'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
            [['vice_user_id'], 'exist', 'targetRelation' => 'viceUser'],
        ];
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function reRegister(): array
    {
        if ($this->base->level >= 5) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: база команды достигла 5-го уровня.'
            ];
        }

        if ($this->buildingBase) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: на базе идет строительство.'
            ];
        }

        if ($this->buildingStadium) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: на стадионе идет строительство.'
            ];
        }

        $player = Player::find()
            ->where(['loan_team_id' => $this->id])
            ->count();
        if ($player) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: в команде находятся арендованные игроки.'
            ];
        }

        $player = Player::find()
            ->where(['team_id' => $this->id])
            ->andWhere(['not', ['loan_team_id' => null]])
            ->count();
        if ($player) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: игроки команды находятся в аренде.'
            ];
        }

        $player = Player::find()
            ->where(['team_id' => $this->id])
            ->andWhere(['not', ['national_id' => null]])
            ->count();
        if ($player) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: в команде есть игроки сборной.'
            ];
        }

        $transfer = Transfer::find()
            ->where(['team_seller_id' => $this->id, 'ready' => null])
            ->count();
        if ($transfer) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: игроки команды выставлены на продажу.'
            ];
        }

        $loan = Loan::find()
            ->where(['team_seller_id' => $this->id, 'ready' => null])
            ->count();
        if ($loan) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: игроки команды выставлены на аренду.'
            ];
        }

        $transfer = Transfer::find()
            ->where(['voted' => null])
            ->andWhere([
                'or',
                ['team_buyer_id' => $this->id],
                ['team_seller_id' => $this->id]
            ])
            ->count();
        if ($transfer) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: по совершенным трансферам команды еще идет голосование.'
            ];
        }

        $loan = Loan::find()
            ->where(['voted' => null])
            ->andWhere([
                'or',
                ['team_buyer_id' => $this->id],
                ['team_seller_id' => $this->id]
            ])
            ->count();
        if ($loan) {
            return [
                'status' => false,
                'message' => 'Перерегистрировать нельзя: по совершенным арендам команды еще идет голосование.'
            ];
        }

        try {
            $playerArray = Player::find()
                ->where(['team_id' => $this->id])
                ->all();
            foreach ($playerArray as $player) {
                /**
                 * @var Player $player
                 */
                $player->makeFree();
            }

            Training::deleteAll([
                'team_id' => $this->id,
                'season_id' => Season::getCurrentSeason()
            ]);
            Scout::deleteAll([
                'team_id' => $this->id,
                'season_id' => Season::getCurrentSeason()
            ]);
            School::deleteAll([
                'team_id' => $this->id,
                'season_id' => Season::getCurrentSeason()
            ]);
            PhysicalChange::deleteAll([
                'team_id' => $this->id,
                'season_id' => Season::getCurrentSeason()
            ]);

            Finance::log([
                'finance_text_id' => FinanceText::TEAM_RE_REGISTER,
                'team_id' => $this->id,
                'value' => self::START_MONEY - $this->finance,
                'value_after' => self::START_MONEY,
                'value_before' => $this->finance,
            ]);

            $this->base_id = 2;
            $this->base_medical_id = 1;
            $this->base_physical_id = 1;
            $this->base_school_id = 1;
            $this->base_scout_id = 1;
            $this->base_training_id = 1;
            $this->finance = self::START_MONEY;
            $this->free_base_number = 5;
            $this->mood_rest = 3;
            $this->mood_super = 3;
            $this->visitor = 100;
            $this->save(true, [
                'base_id',
                'base_medical_id',
                'base_physical_id',
                'base_school_id',
                'base_scout_id',
                'base_training_id',
                'finance',
                'free_base_number',
                'mood_rest',
                'mood_super',
                'visitor',
            ]);

            $this->stadium->capacity = 100;
            $this->stadium->countMaintenance();
            $this->stadium->save(true, [
                'capacity',
                'maintenance',
            ]);

            History::log([
                'history_text_id' => HistoryText::TEAM_RE_REGISTER,
                'team_id' => $this->id,
            ]);

            $this->createPlayers();
            $this->updatePower();
        } catch (Exception $e) {
            ErrorHelper::log($e);
            return [
                'status' => false,
                'message' => 'Не удалось провести перерегистрацию команды',
            ];
        }

        return [
            'status' => true,
            'message' => 'Команда успешно перерегистрирована.',
        ];
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function createPlayers(): bool
    {
        $positions = [
            Position::PROP,
            Position::PROP,
            Position::HOOKER,
            Position::HOOKER,
            Position::PROP,
            Position::PROP,
            Position::LOCK,
            Position::LOCK,
            Position::LOCK,
            Position::LOCK,
            Position::FLANKER,
            Position::FLANKER,
            Position::FLANKER,
            Position::FLANKER,
            Position::EIGHT,
            Position::EIGHT,
            Position::SCRUM_HALF,
            Position::SCRUM_HALF,
            Position::FLY_HALF,
            Position::FLY_HALF,
            Position::WING,
            Position::WING,
            Position::CENTRE,
            Position::CENTRE,
            Position::CENTRE,
            Position::CENTRE,
            Position::WING,
            Position::WING,
            Position::FULL_BACK,
            Position::FULL_BACK,
        ];

        shuffle($positions);

        foreach ($positions as $i => $position) {
            $age = 17 + $i;

            if ($age > 35) {
                $age -= 17;
            }

            $player = new Player();
            $player->age = $age;
            $player->country_id = $this->stadium->city->country_id;
            $player->name_id = NameCountry::getRandNameId($this->stadium->city->country_id);
            $player->physical_id = Physical::getRandPhysicalId();
            $player->power_nominal = $age * 2;
            $player->power_nominal_s = $age * 2;
            $player->school_team_id = $this->id;
            $player->style_id = Style::getRandStyleId();
            $player->surname_id = SurnameCountry::getRandFreeSurnameId(
                $this->id,
                $this->stadium->city->country_id
            );
            $player->team_id = $this->id;
            $player->tire = Player::TIRE_DEFAULT;
            $player->training_ability = random_int(1, 5);
            $player->save();

            $playerPosition = new PlayerPosition();
            $playerPosition->player_id = $player->id;
            $playerPosition->position_id = $position;
            $playerPosition->save();
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function updatePower(): bool
    {
        $player15 = Player::find()
            ->select(['id'])
            ->where(['team_id' => $this->id])
            ->orderBy(['power_nominal' => SORT_DESC])
            ->limit(15)
            ->column();
        $player19 = Player::find()
            ->select(['id'])
            ->where(['team_id' => $this->id])
            ->orderBy(['power_nominal' => SORT_DESC])
            ->limit(19)
            ->column();
        $player24 = Player::find()
            ->select(['id'])
            ->where(['team_id' => $this->id])
            ->orderBy(['power_nominal' => SORT_DESC])
            ->limit(24)
            ->column();
        $power_c_15 = Player::find()->where(['id' => $player15])->sum('power_nominal');
        $power_c_19 = Player::find()->where(['id' => $player19])->sum('power_nominal');
        $power_c_24 = Player::find()->where(['id' => $player24])->sum('power_nominal');
        $power_s_15 = Player::find()->where(['id' => $player15])->sum('power_nominal_s');
        $power_s_19 = Player::find()->where(['id' => $player19])->sum('power_nominal_s');
        $power_s_24 = Player::find()->where(['id' => $player24])->sum('power_nominal_s');
        $power_v = round(($power_c_15 + $power_c_19 + $power_c_24) / 58 * 15);
        $power_vs = round(($power_s_15 + $power_s_19 + $power_s_24) / 58 * 15);

        $this->power_c_15 = $power_c_15;
        $this->power_c_19 = $power_c_19;
        $this->power_c_24 = $power_c_24;
        $this->power_s_15 = $power_s_15;
        $this->power_s_19 = $power_s_19;
        $this->power_s_24 = $power_s_24;
        $this->power_v = $power_v;
        $this->power_vs = $power_vs;
        $this->save(true, [
            'power_c_15',
            'power_c_19',
            'power_c_24',
            'power_s_15',
            'power_s_19',
            'power_s_24',
            'power_v',
            'power_vs',
        ]);

        return true;
    }

    /**
     * @return ForumMessage[]
     */
    public function forumLastArray(): array
    {
        return ForumMessage::find()
            ->select([
                '*',
                'id' => 'MAX(forum_message.id)',
                'date' => 'MAX(forum_message.date)',
            ])
            ->joinWith(['forumTheme.forumGroup'])
            ->where([
                'forum_group.federation_id' => $this->stadium->city->country->federation->id
            ])
            ->groupBy(['forum_theme_id'])
            ->orderBy(['forum_message.id' => SORT_DESC])
            ->limit(5)
            ->all();
    }

    /**
     * @return Game[]
     */
    public function nearestGame(): array
    {
        return Game::find()
            ->joinWith(['schedule'])
            ->andWhere(['or', ['home_team_id' => $this->id], ['guest_team_id' => $this->id]])
            ->andWhere(['played' => null])
            ->orderBy(['date' => SORT_ASC])
            ->limit(2)
            ->all();
    }

    /**
     * @return Game[]
     */
    public function latestGame(): array
    {
        return array_reverse(Game::find()
            ->joinWith(['schedule'])
            ->andWhere(['or', ['home_team_id' => $this->id], ['guest_team_id' => $this->id]])
            ->andWhere(['not', ['played' => null]])
            ->orderBy(['date' => SORT_DESC])
            ->limit(3)
            ->all());
    }

    /**
     * @return int
     */
    public function baseMaintenance(): int
    {
        return $this->base->maintenance_base + $this->base->maintenance_slot * $this->baseUsed();
    }

    /**
     * @return int
     */
    public function baseUsed(): int
    {
        return $this->baseMedical->level
            + $this->basePhysical->level
            + $this->baseSchool->level
            + $this->baseScout->level
            + $this->baseTraining->level;
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

        if ($controller->myTeam->id !== $this->id) {
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

        if ($controller->myTeamOrVice->id !== $this->id) {
            return false;
        }

        return true;
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
        return $controller->user && $controller->user->id === $this->vice_user_id;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        $result = 'Добавить<br/>эмблему';
        if (file_exists(Yii::getAlias('@webroot') . '/img/team/125/' . $this->id . '.png')) {
            $result = Html::img(
                '/img/team/125/' . $this->id . '.png?v=' . filemtime(Yii::getAlias('@webroot') . '/img/team/125/' . $this->id . '.png'),
                [
                    'alt' => $this->name,
                    'class' => 'team-logo',
                    'title' => $this->name,
                ]
            );
        }
        return $result;
    }

    /**
     * @return string
     */
    public function iconFreeTeam(): string
    {
        $result = '';
        if (!$this->user_id) {
            $result = FAS::icon(FAS::_FLAG, ['title' => 'Free team']) . ' ';
        }
        return $result;
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        return $this->name
            . ' (' . $this->stadium->city->name
            . ', ' . $this->stadium->city->country->name
            . ')';
    }

    /**
     * @return string
     */
    public function offSeason(): string
    {
        $result = '-';
        if ($this->offSeason) {
            $result = Html::a(
                $this->offSeason->place . ' место',
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
        $result = '';
        if ($this->championship) {
            $result = Html::a(
                $this->championship->federation->country->name . ', ' .
                $this->championship->division->name . ', ' .
                $this->championship->place . ' ' .
                'место',
                [
                    'championship/index',
                    'countryId' => $this->championship->federation->country->id,
                    'divisionId' => $this->championship->division->id,
                ]
            );
        } elseif ($this->conference) {
            $result = Html::a(
                'Конференция' . ', ' . $this->conference->place . ' место',
                ['conference/table']
            );
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getNumberOfUseSlot(): int
    {
        return $this->baseMedical->level
            + $this->basePhysical->level
            + $this->baseSchool->level
            + $this->baseScout->level
            + $this->baseTraining->level;
    }

    /**
     * @return string
     */
    public function getTeamLink(): string
    {
        return Html::a(
            $this->name,
            ['team/view', 'id' => $this->id],
        );
    }

    /**
     * @return string
     */
    public function getTeamImageLink(): string
    {
        return $this->stadium->city->country->getImage()
            . ' '
            . Html::a(
                $this->name
                . ' <span class="hidden-xs">('
                . $this->stadium->city->name
                . ')</span>',
                ['team/view', 'id' => $this->id]
            );
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
     * @return ActiveQuery
     */
    public function getBase(): ActiveQuery
    {
        return $this->hasOne(Base::class, ['id' => 'base_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseMedical(): ActiveQuery
    {
        return $this->hasOne(BaseMedical::class, ['id' => 'base_medical_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBasePhysical(): ActiveQuery
    {
        return $this->hasOne(BasePhysical::class, ['id' => 'base_physical_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseSchool(): ActiveQuery
    {
        return $this->hasOne(BaseSchool::class, ['id' => 'base_school_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseScout(): ActiveQuery
    {
        return $this->hasOne(BaseScout::class, ['id' => 'base_scout_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBaseTraining(): ActiveQuery
    {
        return $this->hasOne(BaseTraining::class, ['id' => 'base_training_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBuildingBase(): ActiveQuery
    {
        return $this
            ->hasOne(BuildingBase::class, ['team_id' => 'id'])
            ->andWhere(['ready' => null]);
    }

    /**
     * @return ActiveQuery
     */
    public function getBuildingStadium(): ActiveQuery
    {
        return $this
            ->hasOne(BuildingStadium::class, ['team_id' => 'id'])
            ->andWhere(['ready' => null]);
    }

    /**
     * @return ActiveQuery
     */
    public function getChampionship(): ActiveQuery
    {
        return $this
            ->hasOne(Championship::class, ['team_id' => 'id'])
            ->andWhere(['season_id' => Season::getCurrentSeason()]);
    }

    /**
     * @return ActiveQuery
     */
    public function getConference(): ActiveQuery
    {
        return $this
            ->hasOne(Conference::class, ['team_id' => 'id'])
            ->andWhere(['season_id' => Season::getCurrentSeason()]);
    }

    /**
     * @return ActiveQuery
     */
    public function getFederationNews(): ActiveQuery
    {
        return $this->hasOne(News::class, ['id' => 'federation_news_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFriendlyStatus(): ActiveQuery
    {
        return $this->hasOne(FriendlyStatus::class, ['id' => 'friendly_status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNationalAttitude(): ActiveQuery
    {
        return $this->hasOne(Attitude::class, ['id' => 'national_attitude_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOffSeason(): ActiveQuery
    {
        return $this
            ->hasOne(OffSeason::class, ['team_id' => 'id'])
            ->andWhere(['season_id' => Season::getCurrentSeason()]);
    }

    /**
     * @return ActiveQuery
     */
    public function getPresidentAttitude(): ActiveQuery
    {
        return $this->hasOne(Attitude::class, ['id' => 'president_attitude_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRatingTeam(): ActiveQuery
    {
        return $this->hasOne(RatingTeam::class, ['team_id' => 'id']);
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
    public function getTeamRequests(): ActiveQuery
    {
        return $this->hasMany(TeamRequest::class, ['team_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getU19Attitude(): ActiveQuery
    {
        return $this->hasOne(Attitude::class, ['id' => 'u19_attitude_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getU21Attitude(): ActiveQuery
    {
        return $this->hasOne(Attitude::class, ['id' => 'u21_attitude_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getViceUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'vice_user_id']);
    }
}
