<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000008BaseSchool
 * @package console\migrations
 */
class M200101000008BaseSchool extends Migration
{
    private const TABLE = '{{%base_school}}';

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
                'level' => $this->integer(2)->notNull(),
                'player_count' => $this->integer(1)->notNull(),
                'power' => $this->integer(2)->notNull(),
                'price_buy' => $this->integer(7)->notNull(),
                'price_sell' => $this->integer(7)->notNull(),
                'school_speed' => $this->integer(2)->notNull(),
                'with_special' => $this->integer(1)->notNull(),
                'with_style' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('base_school_base_level', self::TABLE, 'base_level', '{{%base}}', 'level');

        $this->batchInsert(
            self::TABLE,
            [
                'base_level',
                'build_speed',
                'level',
                'player_count',
                'power',
                'price_buy',
                'price_sell',
                'school_speed',
                'with_special',
                'with_style'
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [1, 1, 1, 2, 34, 250000, 187500, 14, 0, 0],
                [1, 2, 2, 2, 36, 500000, 375000, 14, 0, 0],
                [2, 3, 3, 2, 38, 750000, 562500, 13, 1, 0],
                [2, 4, 4, 2, 40, 1000000, 750000, 13, 1, 0],
                [3, 5, 5, 2, 42, 1250000, 937500, 12, 2, 0],
                [3, 6, 6, 2, 44, 1500000, 1125000, 12, 2, 0],
                [4, 7, 7, 2, 46, 1750000, 1312500, 11, 2, 1],
                [4, 8, 8, 2, 48, 2000000, 1500000, 11, 2, 1],
                [5, 9, 9, 2, 50, 2250000, 1687500, 10, 2, 2],
                [5, 10, 10, 2, 52, 2500000, 1875000, 10, 2, 2],
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
