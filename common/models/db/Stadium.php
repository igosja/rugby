<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Stadium
 * @package common\models\db
 *
 * @property int $id
 * @property int $capacity
 * @property int $city_id
 * @property int $date
 * @property int $maintenance
 * @property string $name
 *
 * @property-read City $city
 * @property-read Team $team
 */
class Stadium extends AbstractActiveRecord
{
    public const ONE_SIT_PRICE_BUY = 200;
    public const ONE_SIT_PRICE_SELL = 150;
    public const START_CAPACITY = 100;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%stadium}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['capacity', 'city_id', 'name'], 'required'],
            [['capacity'], 'integer', 'min' => 0, 'max' => 99999],
            [['city_id', 'maintenance'], 'integer', 'min' => 1],
            [['name'], 'trim'],
            [['city_id'], 'exist', 'targetRelation' => 'city'],
        ];
    }

    /**
     * @return void
     */
    public function countMaintenance(): void
    {
        $this->maintenance = round(($this->capacity / 60) ** 2);
    }

    /**
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['stadium_id' => 'id']);
    }
}
