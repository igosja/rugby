<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class City
 * @package common\models\db
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 *
 * @property-read Country $country
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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id', 'name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            [['country_id'], 'integer', 'min' => 0, 'max' => 999],
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
