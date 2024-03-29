<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use Exception;
use frontend\controllers\AbstractController;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * Class National
 * @package common\models\db
 *
 * @property int $id
 * @property int $federation_id
 * @property int $finance
 * @property int $mood_rest
 * @property int $mood_super
 * @property int $national_type_id
 * @property int $power_c_15
 * @property int $power_c_19
 * @property int $power_c_24
 * @property int $power_s_15
 * @property int $power_s_19
 * @property int $power_s_24
 * @property int $power_v
 * @property int $power_vs
 * @property int $stadium_id
 * @property int $user_id
 * @property int $vice_user_id
 * @property int $visitor
 *
 * @property-read Federation $federation
 * @property-read NationalType $nationalType
 * @property-read Stadium $stadium
 * @property-read User $user
 * @property-read User $viceUser
 * @property-read WorldCup $worldCup
 */
class National extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%national}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['federation_id', 'national_type_id'], 'required'],
            [['mood_rest', 'mood_super', 'national_type_id'], 'integer', 'min' => 1, 'max' => 9],
            [['federation_id', 'visitor'], 'integer', 'min' => 1, 'max' => 999],
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
            [['finance'], 'integer', 'min' => 0],
            [['stadium_id', 'user_id', 'vice_user_id'], 'integer', 'min' => 1],
            [['federation_id'], 'exist', 'targetRelation' => 'federation'],
            [['national_type_id'], 'exist', 'targetRelation' => 'nationalType'],
            [['stadium_id'], 'exist', 'targetRelation' => 'stadium'],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
            [['vice_user_id'], 'exist', 'targetRelation' => 'viceUser'],
        ];
    }

    /**
     * @return string
     */
    public function division(): string
    {
        $result = '-';
        if ($this->worldCup) {
            $result = Html::a(
                $this->worldCup->division->name . ', ' .
                $this->worldCup->place . ' ' .
                Yii::t('common', 'models.db.national.division.place'),
                [
                    'world-championship/index',
                    'divisionId' => $this->worldCup->division->id,
                    'nationalTypeId' => $this->national_type_id,
                ]
            );
        }
        return $result;
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        return $this->federation->country->name
            . ' (' . $this->nationalType->name
            . ')';
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

        if (!$controller->myNational) {
            return false;
        }

        if ($this->id !== $controller->myNational->id) {
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

        if (!$controller->myNational && !$controller->myNationalVice) {
            return false;
        }

        $myNationalArray = [];
        if ($controller->myNational) {
            $myNationalArray[] = $controller->myNational->id;
        }
        if ($controller->myNationalVice) {
            $myNationalArray[] = $controller->myNationalVice->id;
        }

        if (!in_array($this->id, $myNationalArray, true)) {
            return false;
        }

        return true;
    }

    /**
     * @param bool $image
     * @return string
     */
    public function nationalLink(bool $image = false): string
    {
        $result = '';
        if ($image) {
            $result .= Html::img(
                    '/img/country/12/' . $this->federation->country_id . '.png',
                    [
                        'alt' => $this->federation->country->name,
                        'title' => $this->federation->country->name,
                    ]
                ) . ' ';
        }
        $result .= Html::a(
            $this->federation->country->name . ' (' . $this->nationalType->name . ')',
            ['/national/view', 'id' => $this->id]
        );
        return $result;
    }

    /**
     * @param int $reason
     * @return bool
     * @throws Exception
     */
    public function fireUser(int $reason = FireReason::FIRE_REASON_SELF): bool
    {
        if (!$this->user_id) {
            return true;
        }

        History::log([
            'fire_reason_id' => $reason,
            'text_id' => HistoryText::USER_MANAGER_NATIONAL_OUT,
            'national_id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        if ($this->vice_user_id) {
            History::log([
                'text_id' => HistoryText::USER_VICE_NATIONAL_OUT,
                'national_id' => $this->id,
                'user_id' => $this->vice_user_id,
            ]);

            History::log([
                'text_id' => HistoryText::USER_MANAGER_NATIONAL_IN,
                'national_id' => $this->id,
                'user_id' => $this->vice_user_id,
            ]);
        }

        $this->user_id = $this->vice_user_id;
        $this->vice_user_id = null;
        $this->save(true, ['user_id', 'vice_user_id']);

        if (NationalType::MAIN === $this->national_type_id) {
            $attitudeField = 'national_attitude_id';
        } elseif (NationalType::U21 === $this->national_type_id) {
            $attitudeField = 'u21_attitude_id';
        } else {
            $attitudeField = 'u19_attitude_id';
        }

        foreach ($this->federation->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                $stadium->team->$attitudeField = Attitude::NEUTRAL;
                $stadium->team->save(true, [$attitudeField]);
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function fireVice(): bool
    {
        if (!$this->vice_user_id) {
            return true;
        }

        History::log([
            'history_text_id' => HistoryText::USER_VICE_NATIONAL_OUT,
            'national_id' => $this->id,
            'user_id' => $this->vice_user_id,
        ]);

        $this->vice_user_id = null;
        $this->save(true, ['vice_user_id']);

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
            ->where(['national_id' => $this->id])
            ->orderBy(['power_nominal' => SORT_DESC])
            ->limit(15)
            ->column();
        $player19 = Player::find()
            ->select(['id'])
            ->where(['national_id' => $this->id])
            ->orderBy(['power_nominal' => SORT_DESC])
            ->limit(19)
            ->column();
        $player24 = Player::find()
            ->select(['id'])
            ->where(['national_id' => $this->id])
            ->orderBy(['power_nominal' => SORT_DESC])
            ->limit(24)
            ->column();
        $power_c_15 = Player::find()->where(['id' => $player15])->sum('power_nominal') ?: 0;
        $power_c_19 = Player::find()->where(['id' => $player19])->sum('power_nominal') ?: 0;
        $power_c_24 = Player::find()->where(['id' => $player24])->sum('power_nominal') ?: 0;
        $power_s_15 = Player::find()->where(['id' => $player15])->sum('power_nominal_s') ?: 0;
        $power_s_19 = Player::find()->where(['id' => $player15])->sum('power_nominal_s') ?: 0;
        $power_s_24 = Player::find()->where(['id' => $player15])->sum('power_nominal_s') ?: 0;
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
     * @return Game[]
     */
    public function latestGame(): array
    {
        return array_reverse(Game::find()
            ->joinWith(['schedule'])
            ->where(['or', ['home_national_id' => $this->id], ['guest_national_id' => $this->id]])
            ->andWhere(['not', ['played' => null]])
            ->orderBy(['date' => SORT_DESC])
            ->limit(3)
            ->all());
    }

    /**
     * @return Game[]
     */
    public function nearestGame(): array
    {
        return Game::find()
            ->joinWith(['schedule'])
            ->where(['or', ['home_national_id' => $this->id], ['guest_national_id' => $this->id]])
            ->andWhere(['played' => null])
            ->orderBy(['date' => SORT_ASC])
            ->limit(2)
            ->all();
    }

    /**
     * @return int
     */
    public function attitudeNational(): int
    {
        $result = 0;
        foreach ($this->federation->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if ($stadium->team->user_id) {
                    $result++;
                }
            }
        }
        if (!$result) {
            $result = 1;
        }
        return $result;
    }

    /**
     * @return int
     */
    public function attitudeNationalNegative(): int
    {
        $result = 0;
        foreach ($this->federation->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if ($stadium->team->user_id && Attitude::NEGATIVE === $stadium->team->national_attitude_id) {
                    $result++;
                }
            }
        }
        return round($result / $this->attitudeNational() * 100);
    }

    /**
     * @return int
     */
    public function attitudeNationalNeutral(): int
    {
        $result = 0;
        foreach ($this->federation->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if ($stadium->team->user_id && Attitude::NEUTRAL === $stadium->team->national_attitude_id) {
                    $result++;
                }
            }
        }
        return round($result / $this->attitudeNational() * 100);
    }

    /**
     * @return int
     */
    public function attitudeNationalPositive(): int
    {
        $result = 0;
        foreach ($this->federation->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if ($stadium->team->user_id && Attitude::POSITIVE === $stadium->team->national_attitude_id) {
                    $result++;
                }
            }
        }
        return round($result / $this->attitudeNational() * 100);
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
    public function getNationalType(): ActiveQuery
    {
        return $this->hasOne(NationalType::class, ['id' => 'national_type_id']);
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

    /**
     * @return ActiveQuery
     */
    public function getWorldCup(): ActiveQuery
    {
        return $this
            ->hasOne(WorldCup::class, ['national_id' => 'id'])
            ->andWhere(['season_id' => Season::getCurrentSeason()]);
    }
}
