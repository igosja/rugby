<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200106_155411_base_physical
 * @package console\migrations
 */
class m200106_155411_base_physical extends Migration
{
    private const TABLE = '{{%base_physical}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(2),
            'base_level' => $this->integer(1)->notNull(),
            'build_speed' => $this->integer(2)->notNull(),
            'change_count' => $this->integer(2)->notNull(),
            'level' => $this->integer(2)->notNull(),
            'price_buy' => $this->integer(7)->notNull(),
            'price_sell' => $this->integer(7)->notNull(),
            'tire_bonus' => $this->integer(1)->notNull(),
        ]);

        $this->addForeignKey('base_physical_base_level', self::TABLE, 'base_level', '{{%base}}', 'level');

        $this->batchInsert(self::TABLE, [
            'base_level',
            'build_speed',
            'change_count',
            'level',
            'price_buy',
            'price_sell',
            'tire_bonus'
        ], [
            [0, 0, 0, 0, 0, 0, 2],
            [1, 1, 5, 1, 250000, 187500, 1],
            [1, 2, 10, 2, 500000, 375000, 1],
            [2, 3, 15, 3, 750000, 562500, 0],
            [2, 4, 20, 4, 1000000, 750000, 0],
            [3, 5, 25, 5, 1250000, 937500, -1],
            [3, 6, 30, 6, 1500000, 1125000, -1],
            [4, 7, 35, 7, 1750000, 1312500, -2],
            [4, 8, 40, 8, 2000000, 1500000, -2],
            [5, 9, 45, 9, 2250000, 1687500, -3],
            [5, 10, 50, 10, 2500000, 1875000, -3],
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
