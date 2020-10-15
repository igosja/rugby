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
                'build_speed' => $this->integer(2)->notNull(),
                'level' => $this->integer(2)->notNull()->unique(),
                'min_base_level' => $this->integer(1)->notNull(),
                'player_count' => $this->integer(1)->notNull(),
                'power' => $this->integer(2)->notNull(),
                'price_buy' => $this->integer(7)->notNull(),
                'price_sell' => $this->integer(7)->notNull(),
                'school_speed' => $this->integer(2)->notNull(),
                'with_special' => $this->integer(1)->notNull(),
                'with_style' => $this->integer(1)->notNull(),
            ]
        );

        $this->addForeignKey('base_school_min_base_level', self::TABLE, 'min_base_level', '{{%base}}', 'level');

        $this->batchInsert(
            self::TABLE,
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
                'with_style'
            ],
            [
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [1, 1, 1, 2, 34, 250000, 187500, 14, 0, 0],
                [2, 2, 1, 2, 36, 500000, 375000, 14, 0, 0],
                [3, 3, 2, 2, 38, 750000, 562500, 13, 1, 0],
                [4, 4, 2, 2, 40, 1000000, 750000, 13, 1, 0],
                [5, 5, 3, 2, 42, 1250000, 937500, 12, 2, 0],
                [6, 6, 3, 2, 44, 1500000, 1125000, 12, 2, 0],
                [7, 7, 4, 2, 46, 1750000, 1312500, 11, 2, 1],
                [8, 8, 4, 2, 48, 2000000, 1500000, 11, 2, 1],
                [9, 9, 5, 2, 50, 2250000, 1687500, 10, 2, 2],
                [10, 10, 5, 2, 52, 2500000, 1875000, 10, 2, 2],
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
