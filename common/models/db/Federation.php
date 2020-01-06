<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Federation
 * @package common\models\db
 *
 * @property int $federation_id
 * @property int $federation_auto
 * @property int $federation_country_id
 * @property int $federation_finance
 * @property int $federation_game
 * @property int $federation_president_id
 * @property int $federation_stadium_capacity
 * @property int $federation_vice_id
 *
 * @property Country $country
 * @property User $president
 * @property User $vice
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
     * @return int
     */
    public function attitudePresident(): int
    {
        $result = 0;
        foreach ($this->country->cities as $city) {
            foreach ($city->stadiums as $stadium) {
                if ($stadium->team->team_user_id) {
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
                if (Attitude::NEGATIVE == $stadium->team->team_attitude_president && $stadium->team->team_user_id) {
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
                if (Attitude::NEUTRAL == $stadium->team->team_attitude_president && $stadium->team->team_user_id) {
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
                if (Attitude::POSITIVE == $stadium->team->team_attitude_president && $stadium->team->team_user_id) {
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
        return $this->hasOne(Country::class, ['country_id' => 'federation_country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPresident(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'federation_president_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getVice(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'federation_vice_id']);
    }
}
