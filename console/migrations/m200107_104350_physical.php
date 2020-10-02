<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104350_physical
 * @package console\migrations
 */
class m200107_104350_physical extends Migration
{
    private const TABLE = '{{%physical}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'physical_id' => $this->primaryKey(2),
            'physical_name' => $this->string(20),
            'physical_opposite' => $this->integer(2)->defaultValue(0),
            'physical_value' => $this->integer(3)->defaultValue(0),
        ]);

        $this->batchInsert(self::TABLE, ['physical_name', 'physical_opposite', 'physical_value'], [
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
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
