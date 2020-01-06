<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Stadium
 * @package common\models\db
 *
 * @property int $stadium_id
 * @property int $stadium_capacity
 * @property int $stadium_city_id
 * @property int $stadium_date
 * @property int $stadium_maintenance
 * @property string $stadium_name
 *
 * @property City $city
 * @property Team $team
 */
class Stadium extends AbstractActiveRecord
{
    const ONE_SIT_PRICE_BUY = 200;
    const ONE_SIT_PRICE_SELL = 150;
    const START_CAPACITY = 100;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%stadium}}';
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
            $this->stadium_capacity = self::START_CAPACITY;
            $this->stadium_date = time();
            $this->stadium_maintenance = $this->countMaintenance();
        }
        return true;
    }

    /**
     * @return int
     */
    public function countMaintenance(): int
    {
        return round(pow($this->stadium_capacity / 35, 2));
    }

    /**
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['city_id' => 'stadium_city_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['team_stadium_id' => 'stadium_id']);
    }
}
