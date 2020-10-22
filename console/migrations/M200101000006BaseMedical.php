<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000006BaseMedical
 * @package console\migrations
 */
class M200101000006BaseMedical extends Migration
{
    private const TABLE = '{{%base_medical}}';

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
                'price_buy' => $this->integer(7)->notNull(),
                'price_sell' => $this->integer(7)->notNull(),
                'tire' => $this->integer(2)->notNull(),
            ]
        );

        $this->addForeignKey('base_medical_min_base_level', self::TABLE, 'min_base_level', '{{%base}}', 'level');

        $this->batchInsert(
            self::TABLE,
            [
                'build_speed',
                'level',
                'min_base_level',
                'price_buy',
                'price_sell',
                'tire'
            ],
            [
                [0, 0, 0, 0, 0, 50],
                [1, 1, 1, 250000, 187500, 45],
                [2, 2, 1, 500000, 375000, 40],
                [3, 3, 2, 750000, 562500, 35],
                [4, 4, 2, 1000000, 750000, 30],
                [5, 5, 3, 1250000, 937500, 25],
                [6, 6, 3, 1500000, 1125000, 20],
                [7, 7, 4, 1750000, 1312500, 15],
                [8, 8, 4, 2000000, 1500000, 10],
                [9, 9, 5, 2250000, 1687500, 5],
                [10, 10, 5, 2500000, 1875000, 0],
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
