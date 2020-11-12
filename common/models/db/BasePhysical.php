<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class BasePhysical
 * @package common\models\db
 *
 * @property int $id
 * @property int $build_speed
 * @property int $change_count
 * @property int $level
 * @property int $min_base_level
 * @property int $price_buy
 * @property int $price_sell
 * @property int $tire_bonus
 *
 * @property-read Base $base
 */
class BasePhysical extends AbstractActiveRecord
{
    public const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_physical}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                ['build_speed', 'change_count', 'level', 'min_base_level', 'price_buy', 'price_sell', 'tire_bonus'],
                'required'
            ],
            [['build_speed', 'level'], 'integer', 'min' => 0, 'max' => 99],
            [['min_base_level'], 'integer', 'min' => 0, 'max' => 9],
            [['tire_bonus'], 'integer', 'min' => -9, 'max' => 9],
            [['price_buy', 'price_sell'], 'integer', 'min' => 0, 'max' => 9999999],
            [['price_buy'], 'compare', 'compareAttribute' => 'price_sell', 'operator' => '>='],
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
