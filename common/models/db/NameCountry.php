<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class NameCountry
 * @package common\models\db
 *
 * @property int $country_id
 * @property int $name_id
 *
 * @property-read Country $country
 * @property-read Name $name
 */
class NameCountry extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%name_country}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id', 'name_id'], 'required'],
            [
                ['name_id'],
                'unique',
                'filter' => function (Query $query): Query {
                    return $query->andWhere(['country_id' => $this->country_id]);
                }
            ],
            [['country_id'], 'integer', 'min' => 1, 'max' => 999],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['name_id'], 'exist', 'targetRelation' => 'name'],
        ];
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
    public function getName(): ActiveQuery
    {
        return $this->hasOne(Name::class, ['id' => 'name_id']);
    }
}
