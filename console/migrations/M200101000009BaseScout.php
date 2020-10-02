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
                'base_level' => $this->integer(1)->notNull(),
                'build_speed' => $this->integer(2)->notNull(),
                'distance' => $this->integer(1)->notNull(),
                'level' => $this->integer(2)->notNull(),
                'market_game_row' => $this->integer(1)->notNull(),
                'market_physical' => $this->integer(1)->notNull(),
                'market_tire' => $this->integer(1)->notNull(),
                'my_style_count' => $this->integer(2)->notNull(),
                'my_style_price' => $this->integer(6)->notNull(),
                'opponent_game_row' => $this->integer(1)->notNull(),
                'opponent_physical' => $this->integer(1)->notNull(),
                'opponent_tire' => $this->integer(1)->notNull(),
                'price_buy' => $this->integer(7)->notNull(),
                'price_sell' => $this->integer(7)->notNull(),
                'scout_speed_max' => $this->integer(3)->notNull(),
                'scout_speed_min' => $this->integer(3)->notNull(),
            ]
        );

        $this->addForeignKey('base_scout_base_level', self::TABLE, 'base_level', '{{%base}}', 'level');

        $this->batchInsert(
            self::TABLE,
            [
                'base_level',
                'build_speed',
                'distance',
                'level',
                'market_game_row',
                'market_physical',
                'market_tire',
                'my_style_count',
                'my_style_price',
                'opponent_game_row',
                'opponent_physical',
                'opponent_tire',
                'price_buy',
                'price_sell',
                'scout_speed_max',
                'scout_speed_min'
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [1, 1, 1, 1, 0, 0, 0, 5, 25000, 1, 0, 0, 250000, 187500, 15, 5],
                [1, 2, 1, 2, 0, 0, 0, 10, 50000, 1, 0, 1, 500000, 375000, 25, 15],
                [2, 3, 2, 3, 0, 0, 0, 15, 75000, 1, 1, 1, 750000, 562500, 35, 25],
                [2, 4, 2, 4, 1, 0, 0, 20, 100000, 1, 1, 1, 1000000, 750000, 45, 35],
                [3, 5, 3, 5, 1, 0, 1, 25, 125000, 1, 1, 1, 1250000, 937500, 55, 45],
                [3, 6, 3, 6, 1, 1, 1, 30, 150000, 1, 1, 1, 1500000, 1125000, 65, 55],
                [4, 7, 4, 7, 1, 1, 1, 35, 175000, 1, 1, 1, 1750000, 1312500, 75, 65],
                [4, 8, 4, 8, 1, 1, 1, 40, 200000, 1, 1, 1, 2000000, 1500000, 85, 75],
                [5, 9, 5, 9, 1, 1, 1, 45, 225000, 1, 1, 1, 2250000, 1687500, 95, 85],
                [5, 10, 5, 10, 1, 1, 1, 50, 250000, 1, 1, 1, 2500000, 1875000, 100, 100],
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
