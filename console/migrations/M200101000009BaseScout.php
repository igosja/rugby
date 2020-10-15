<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000009BaseScout
 * @package console\migrations
 */
class M200101000009BaseScout extends Migration
{
    private const TABLE = '{{%base_scout}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'build_speed' => $this->integer(2)->notNull(),
                'distance' => $this->integer(1)->notNull(),
                'is_market_game_row' => $this->boolean()->notNull(),
                'is_market_physical' => $this->boolean()->notNull(),
                'is_market_tire' => $this->boolean()->notNull(),
                'is_opponent_game_row' => $this->boolean()->notNull(),
                'is_opponent_physical' => $this->boolean()->notNull(),
                'is_opponent_tire' => $this->boolean()->notNull(),
                'level' => $this->integer(2)->notNull()->unique(),
                'min_base_level' => $this->integer(1)->notNull(),
                'my_style_count' => $this->integer(2)->notNull(),
                'my_style_price' => $this->integer(6)->notNull(),
                'price_buy' => $this->integer(7)->notNull(),
                'price_sell' => $this->integer(7)->notNull(),
                'scout_speed_max' => $this->integer(3)->notNull(),
                'scout_speed_min' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('base_scout_min_base_level', self::TABLE, 'min_base_level', '{{%base}}', 'level');

        $this->batchInsert(
            self::TABLE,
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
                'scout_speed_min'
            ],
            [
                [0, 0, false, false, false, false, false, false, 0, 0, 0, 0, 0, 0, 0, 0],
                [1, 1, false, false, false, true, false, false, 1, 1, 5, 25000, 250000, 187500, 15, 5],
                [2, 1, false, false, false, true, false, true, 2, 1, 10, 50000, 500000, 375000, 25, 15],
                [3, 2, false, false, false, true, true, true, 3, 2, 15, 75000, 750000, 562500, 35, 25],
                [4, 2, true, false, false, true, true, true, 4, 2, 20, 100000, 1000000, 750000, 45, 35],
                [5, 3, true, false, true, true, true, true, 5, 3, 25, 125000, 1250000, 937500, 55, 45],
                [6, 3, true, true, true, true, true, true, 6, 3, 30, 150000, 1500000, 1125000, 65, 55],
                [7, 4, true, true, true, true, true, true, 7, 4, 35, 175000, 1750000, 1312500, 75, 65],
                [8, 4, true, true, true, true, true, true, 8, 4, 40, 200000, 2000000, 1500000, 85, 75],
                [9, 5, true, true, true, true, true, true, 9, 5, 45, 225000, 2250000, 1687500, 95, 85],
                [10, 5, true, true, true, true, true, true, 10, 5, 50, 250000, 2500000, 1875000, 100, 100],
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
