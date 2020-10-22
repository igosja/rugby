<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class SurnameCountry
 * @package common\models\db
 *
 * @property int $country_id
 * @property int $surname_id
 *
 * @property-read Country $country
 * @property-read Surname $surname
 */
class SurnameCountry extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%surname_country}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['country_id', 'surname_id'], 'required'],
            [
                ['surname_id'],
                'unique',
                'filter' => function (Query $query): Query {
                    return $query->andWhere(['country_id' => $this->country_id]);
                }
            ],
            [['country_id'], 'integer', 'min' => 1, 'max' => 999],
            [['country_id'], 'exist', 'targetRelation' => 'country'],
            [['surname_id'], 'exist', 'targetRelation' => 'surname'],
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
    public function getSurname(): ActiveQuery
    {
        return $this->hasOne(Surname::class, ['id' => 'surname_id'])->cache();
    }
}
