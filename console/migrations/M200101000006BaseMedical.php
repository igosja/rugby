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
                'base_level' => $this->integer(1)->notNull(),
                'build_speed' => $this->integer(2)->notNull(),
                'level' => $this->integer(2)->notNull(),
                'price_buy' => $this->integer(7)->notNull(),
                'price_sell' => $this->integer(7)->notNull(),
                'tire' => $this->integer(2)->notNull(),
            ]
        );

        $this->addForeignKey('base_medical_base_level', self::TABLE, 'base_level', '{{%base}}', 'level');

        $this->batchInsert(
            self::TABLE,
            [
                'base_level',
                'build_speed',
                'level',
                'price_buy',
                'price_sell',
                'tire'
            ],
            [
                [0, 0, 0, 0, 0, 50],
                [1, 1, 1, 250000, 187500, 45],
                [1, 2, 2, 500000, 375000, 40],
                [2, 3, 3, 750000, 562500, 35],
                [2, 4, 4, 1000000, 750000, 30],
                [3, 5, 5, 1250000, 937500, 25],
                [3, 6, 6, 1500000, 1125000, 20],
                [4, 7, 7, 1750000, 1312500, 15],
                [4, 8, 8, 2000000, 1500000, 10],
                [5, 9, 9, 2250000, 1687500, 5],
                [5, 10, 10, 2500000, 1875000, 0],
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
