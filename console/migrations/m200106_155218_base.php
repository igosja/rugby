<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200106_155218_base
 * @package console\migrations
 */
class m200106_155218_base extends Migration
{
    private const TABLE = '{{%base}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(2),
            'build_speed' => $this->integer(2)->notNull(),
            'level' => $this->integer(2)->notNull(),
            'maintenance_base' => $this->integer(7)->notNull(),
            'maintenance_slot' => $this->integer(6)->notNull(),
            'price_buy' => $this->integer(8)->notNull(),
            'price_sell' => $this->integer(7)->notNull(),
            'slot_max' => $this->integer(2)->notNull(),
            'slot_min' => $this->integer(2)->notNull(),
        ]);

        $this->batchInsert(self::TABLE, [
            'build_speed',
            'level',
            'maintenance_base',
            'maintenance_slot',
            'price_buy',
            'price_sell',
            'slot_max',
            'slot_min',
        ], [
            [0, 0, 0, 0, 0, 0, 0, 0],
            [2, 1, 50000, 25000, 500000, 375000, 5, 0],
            [4, 2, 100000, 50000, 1000000, 750000, 10, 3],
            [6, 3, 150000, 75000, 2000000, 1500000, 15, 8],
            [8, 4, 200000, 100000, 3000000, 2250000, 20, 13],
            [10, 5, 250000, 125000, 4000000, 3000000, 25, 18],
            [12, 6, 300000, 150000, 5000000, 3750000, 30, 23],
            [14, 7, 350000, 175000, 6000000, 4500000, 35, 28],
            [16, 8, 400000, 200000, 7000000, 5250000, 40, 33],
            [18, 9, 450000, 225000, 8000000, 6000000, 45, 38],
            [20, 10, 500000, 250000, 10000000, 7500000, 50, 43],
        ]);

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
