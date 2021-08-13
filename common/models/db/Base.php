<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Base
 * @package common\models\db
 *
 * @property int $id
 * @property int $build_speed
 * @property int $level
 * @property int $maintenance_base
 * @property int $maintenance_slot
 * @property int $price_buy
 * @property int $price_sell
 * @property int $slot_max
 * @property int $slot_min
 */
class Base extends AbstractActiveRecord
{
    public const START_LEVEL = 2;
    public const FREE_SLOTS = 5;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base}}';
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
                    'maintenance_base',
                    'maintenance_slot',
                    'price_buy',
                    'price_sell',
                    'slot_max',
                    'slot_min'
                ],
                'required'
            ],
            [['build_speed', 'level', 'slot_max', 'slot_mim'], 'integer', 'min' => 0, 'max' => 99],
            [['maintenance_base', 'price_sell'], 'integer', 'min' => 0, 'max' => 9999999],
            [['maintenance_slot'], 'integer', 'min' => 0, 'max' => 999999],
            [['price_buy'], 'integer', 'min' => 0, 'max' => 99999999],
            [['price_buy'], 'compare', 'compareAttribute' => 'price_sell', 'operator' => '>='],
            [['slot_max'], 'compare', 'compareAttribute' => 'slot_min', 'operator' => '>='],
            [['level'], 'unique'],
        ];
    }
}
