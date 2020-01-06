<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class City
 * @package common\models\db
 *
 * @property int $city_id
 * @property int $city_country_id
 * @property string $city_name
 *
 * @property Country $country
 * @property Stadium[] $stadiums
 */
class City extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%city}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['country_id' => 'city_country_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStadiums(): ActiveQuery
    {
        return $this->hasMany(Stadium::class, ['stadium_city_id' => 'city_id']);
    }
}
