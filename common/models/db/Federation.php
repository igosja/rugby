<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use common\components\helpers\ErrorHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * Class Federation
 * @package common\models\db
 *
 * @property int $id
 * @property int $auto
 * @property int $country_id
 * @property int $finance
 * @property int $game
 * @property int $president_user_id
 * @property int $stadium_capacity
 * @property int $vice_user_id
 *
 * @property-read Country $country
 * @property-read LeagueCoefficient[] $leagueCoefficients
 * @property-read User $presidentUser
 * @property-read User $viceUser
 */
class Federation extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%federation}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id'], 'required'],
            [['country_id'], 'integer', 'min' => 0, 'max' => 999],
            [['auto', 'game', 'stadium_capacity'], 'integer', 'min' => 0, 'max' => 99999],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['president_user_id'], 'exist', 'targetRelation' => 'presidentUser'],
            [['vice_user_id'], 'exist', 'targetRelation' => 'viceUser'],
        ];
    }

    /**
     * @param int $reason
     * @return bool
     * @throws Exception
     */
    public function firePresident(int $reason = FireReason::FIRE_REASON_SELF): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            History::log([
                'fire_reason_id' => $reason,
                'federation_id' => $this->id,
                'history_text_id' => HistoryText::USER_PRESIDENT_OUT,
                'user_id' => $this->president_user_id,
            ]);
            History::log([
                'federation_id' => $this->id,
                'history_text_id' => HistoryText::USER_VICE_PRESIDENT_OUT,
                'user_id' => $this->vice_user_id,
            ]);
            History::log([
                'federation_id' => $this->id,
                'history_text_id' => HistoryText::USER_PRESIDENT_IN,
                'user_id' => $this->vice_user_id,
            ]);

            $this->president_user_id = $this->vice_user_id;
            $this->vice_user_id = null;
            $this->save(true, ['country_president_id', 'country_president_vice_id']);

            foreach ($this->country->cities as $city) {
                foreach ($city->stadiums as $stadium) {
                    $stadium->team->president_attitude_id = Attitude::NEUTRAL;
                    $stadium->team->save(true, ['president_attitude_id']);
                }
            }
        } catch (Exception $e) {
            ErrorHelper::log($e);
            if ($transaction) {
                $transaction->rollBack();
            }
            return false;
        }

        $transaction->commit();
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function fireVicePresident(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            History::log([
                'federation_id' => $this->id,
                'history_text_id' => HistoryText::USER_VICE_PRESIDENT_OUT,
                'user_id' => $this->vice_user_id,
            ]);

            $this->vice_user_id = null;
            $this->save(true, ['vice_user_id']);
        } catch (Exception $e) {
            ErrorHelper::log($e);
            if ($transaction) {
                $transaction->rollBack();
            }
            return false;
        }

        $transaction->commit();
        return true;
    }

    /**
     * @return int
     */
    public function attitudePresident(): int
    {
        $result = 0;
        foreach ($this->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if ($stadium->team->user) {
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
    public function attitudePresidentNegative(): int
    {
        $result = 0;
        foreach ($this->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if (Attitude::NEGATIVE === $stadium->team->president_attitude_id && $stadium->team->user_id) {
                    $result++;
                }
            }
        }
        return round($result / $this->attitudePresident() * 100);
    }

    /**
     * @return int
     */
    public function attitudePresidentNeutral(): int
    {
        $result = 0;
        foreach ($this->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if (Attitude::NEUTRAL === $stadium->team->president_attitude_id && $stadium->team->user_id) {
                    $result++;
                }
            }
        }
        return round($result / $this->attitudePresident() * 100);
    }

    /**
     * @return int
     */
    public function attitudePresidentPositive(): int
    {
        $result = 0;
        foreach ($this->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if (Attitude::POSITIVE === $stadium->team->president_attitude_id && $stadium->team->user_id) {
                    $result++;
                }
            }
        }
        return round($result / $this->attitudePresident() * 100);
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
    public function getLeagueCoefficients(): ActiveQuery
    {
        return $this->hasMany(LeagueCoefficient::class, ['federation_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPresidentUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'president_user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getViceUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'vice_user_id']);
    }
}
