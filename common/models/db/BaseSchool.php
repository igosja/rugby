<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class BaseSchool
 * @package common\models\db
 *
 * @property int $id
 * @property int $build_speed
 * @property int $level
 * @property int $min_base_level
 * @property int $player_count
 * @property int $power
 * @property int $price_buy
 * @property int $price_sell
 * @property int $school_speed
 * @property int $with_special
 * @property int $with_style
 *
 * @property-read Base $base
 */
class BaseSchool extends AbstractActiveRecord
{
    public const START_LEVEL = 1;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_school}}';
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
                    'player_count',
                    'power',
                    'price_buy',
                    'price_sell',
                    'school_speed',
                    'with_special',
                    'with_style',
                ],
                'required'
            ],
            [['build_speed', 'level', 'power', 'school_speed'], 'integer', 'min' => 0, 'max' => 99],
            [['min_base_level', 'player_count', 'with_special', 'with_style'], 'integer', 'min' => 0, 'max' => 9],
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
