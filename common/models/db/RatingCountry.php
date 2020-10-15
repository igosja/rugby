<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class RatingCountry
 * @package common\models\db
 *
 * @property int $id
 * @property int $auto_place
 * @property int $country_id
 * @property int $league_place
 * @property int $stadium_place
 *
 * @property-read Country $country
 */
class RatingCountry extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_country}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['country_id'], 'required'],
            [['auto_place', 'country_id', 'league_place', 'stadium_place'], 'integer', 'min' => 0, 'max' => 999],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }
}
