<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000032Physical
 * @package console\migrations
 */
class M200101000032Physical extends Migration
{
    private const TABLE = '{{%physical}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(20)->notNull(),
                'opposite_id' => $this->integer(2)->notNull(),
                'value' => $this->integer(3)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'opposite_id', 'value'],
            [
                ['125%, падает', 1, 125],
                ['120%, падает', 20, 120],
                ['115%, падает', 19, 115],
                ['110%, падает', 18, 110],
                ['105%, падает', 17, 105],
                ['100%, падает', 16, 100],
                ['95%, падает', 15, 95],
                ['90%, падает', 14, 90],
                ['85%, падает', 13, 85],
                ['80%, падает', 12, 80],
                ['75%, растет', 11, 75],
                ['80%, растет', 10, 80],
                ['85%, растет', 9, 85],
                ['90%, растет', 8, 90],
                ['95%, растет', 7, 95],
                ['100%, растет', 6, 100],
                ['105%, растет', 5, 105],
                ['110%, растет', 4, 110],
                ['115%, растет', 3, 115],
                ['120%, растет', 2, 120],
            ]
        );

        $this->addForeignKey('physical_opposite_id', self::TABLE, 'opposite_id', self::TABLE, 'id');

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
