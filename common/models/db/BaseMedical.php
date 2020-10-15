<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class BaseMedical
 * @package common\models\db
 *
 * @property int $id
 * @property int $build_speed
 * @property int $level
 * @property int $min_base_level
 * @property int $price_buy
 * @property int $price_sell
 * @property int $tire
 *
 * @property-read Base $base
 */
class BaseMedical extends AbstractActiveRecord
{
    public const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_medical}}';
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['build_speed', 'level', 'min_base_level', 'price_buy', 'price_sell', 'tire'], 'required'],
            [['build_speed', 'level', 'tire'], 'integer', 'min' => 0, 'max' => 99],
            [['min_base_level'], 'integer', 'min' => 0, 'max' => 9],
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
