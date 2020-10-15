<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000007BasePhysical
 * @package console\migrations
 */
class M200101000007BasePhysical extends Migration
{
    private const TABLE = '{{%base_physical}}';

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
                'change_count' => $this->integer(2)->notNull(),
                'level' => $this->integer(2)->notNull()->unique(),
                'min_base_level' => $this->integer(1)->notNull(),
                'price_buy' => $this->integer(7)->notNull(),
                'price_sell' => $this->integer(7)->notNull(),
                'tire_bonus' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('base_physical_min_base_level', self::TABLE, 'min_base_level', '{{%base}}', 'level');

        $this->batchInsert(
            self::TABLE,
            [
                'build_speed',
                'change_count',
                'level',
                'min_base_level',
                'price_buy',
                'price_sell',
                'tire_bonus'
            ],
            [
                [0, 0, 0, 0, 0, 0, 2],
                [1, 5, 1, 1, 250000, 187500, 1],
                [2, 10, 2, 1, 500000, 375000, 1],
                [3, 15, 3, 2, 750000, 562500, 0],
                [4, 20, 4, 2, 1000000, 750000, 0],
                [5, 25, 5, 3, 1250000, 937500, -1],
                [6, 30, 6, 3, 1500000, 1125000, -1],
                [7, 35, 7, 4, 1750000, 1312500, -2],
                [8, 40, 8, 4, 2000000, 1500000, -2],
                [9, 45, 9, 5, 2250000, 1687500, -3],
                [10, 50, 10, 5, 2500000, 1875000, -3],
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
