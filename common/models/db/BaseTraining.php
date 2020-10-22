<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class BaseTraining
 * @package common\models\db
 *
 * @property int $id
 * @property int $build_speed
 * @property int $level
 * @property int $min_base_level
 * @property int $position_count
 * @property int $position_price
 * @property int $power_count
 * @property int $power_price
 * @property int $price_buy
 * @property int $price_sell
 * @property int $special_count
 * @property int $special_price
 * @property int $training_speed_max
 * @property int $training_speed_min
 *
 * @property-read Base $base
 */
class BaseTraining extends AbstractActiveRecord
{
    public const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_training}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'build_speed',
                    'level',
                    'min_base_level',
                    'position_count',
                    'position_price',
                    'power_count',
                    'power_price',
                    'price_buy',
                    'price_sell',
                    'special_count',
                    'special_price',
                    'training_speed_max',
                    'training_speed_min',
                ],
                'required'
            ],
            [['build_speed', 'level', 'power_count'], 'integer', 'min' => 0, 'max' => 99],
            [['training_speed_max', 'training_speed_min'], 'integer', 'min' => 0, 'max' => 999],
            [['position_price', 'power_price', 'special_price'], 'integer', 'min' => 0, 'max' => 999999],
            [['min_base_level', 'position_count', 'special_count'], 'integer', 'min' => 0, 'max' => 9],
            [['price_buy', 'price_sell'], 'integer', 'min' => 0, 'max' => 9999999],
            [['price_buy'], 'compare', 'compareAttribute' => 'price_sell', 'operator' => '>='],
            [['training_speed_max'], 'compare', 'compareAttribute' => 'training_speed_min', 'operator' => '>='],
            [['level'], 'unique'],
            [['min_base_level'], 'exist', 'targetRelation' => 'base'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBase(): ActiveQuery
    {
        return $this->hasOne(Base::class, ['level' => 'min_base_level']);
    }
}
