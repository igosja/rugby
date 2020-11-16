<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
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
 * @property-read Stadium $stadium
 * @property-read TeamRequest[] $teamRequests
 * @property-read Attitude $u19Attitude
 * @property-read Attitude $u21Attitude
 * @property-read User $user
 * @property-read User $viceUser
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
        } else {
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
