<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class BaseScout
 * @package common\models\db
 *
 * @property int $id
 * @property int $build_speed
 * @property int $distance
 * @property bool $is_market_game_row
 * @property bool $is_market_physical
 * @property bool $is_market_tire
 * @property bool $is_opponent_game_row
 * @property bool $is_opponent_physical
 * @property bool $is_opponent_tire
 * @property int $level
 * @property int $min_base_level
 * @property int $my_style_count
 * @property int $my_style_price
 * @property int $price_buy
 * @property int $price_sell
 * @property int $scout_speed_max
 * @property int $scout_speed_min
 *
 * @property-read Base $base
 */
class BaseScout extends AbstractActiveRecord
{
    public const START_LEVEL = 1;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [
                [
                    'build_speed',
                    'distance',
                    'is_market_game_row',
                    'is_market_physical',
                    'is_market_tire',
                    'is_opponent_game_row',
                    'is_opponent_physical',
                    'is_opponent_tire',
                    'level',
                    'min_base_level',
                    'my_style_count',
                    'my_style_price',
                    'price_buy',
                    'price_sell',
                    'scout_speed_max',
                    'scout_speed_min',
                ],
                'required'
            ],
            [
                [
                    'is_market_game_row',
                    'is_market_physical',
                    'is_market_tire',
                    'is_opponent_game_row',
                    'is_opponent_physical',
                    'is_opponent_tire',
                ],
                'boolean'
            ],
            [['build_speed', 'level', 'my_style_count'], 'integer', 'min' => 0, 'max' => 99],
            [['scout_speed_max', 'scout_speed_min'], 'integer', 'min' => 0, 'max' => 999],
            [['my_style_price'], 'integer', 'min' => 0, 'max' => 999999],
            [
                ['distance', 'min_base_level', 'player_count', 'with_special', 'with_style'],
                'integer',
                'min' => 0,
                'max' => 9
            ],
            [['price_buy', 'price_sell'], 'integer', 'min' => 0, 'max' => 9999999],
            [['price_buy'], 'compare', 'compareAttribute' => 'price_sell', 'operator' => '>='],
            [['scout_speed_max'], 'compare', 'compareAttribute' => 'scout_speed_min', 'operator' => '>='],
            [['level'], 'unique'],
            [['min_base_level'], 'exist', 'targetRelation' => 'base'],
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%base_scout}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getBase(): ActiveQuery
    {
        return $this->hasOne(Base::class, ['level' => 'min_base_level']);
    }
}
