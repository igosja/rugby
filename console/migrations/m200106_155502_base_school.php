<?php

use yii\db\Migration;

/**
 * Class m200106_155502_base_school
 */
class m200106_155502_base_school extends Migration
{
    const TABLE = '{{%base_school}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'base_school_id' => $this->primaryKey(2),
            'base_school_base_level' => $this->integer(1)->defaultValue(0),
            'base_school_build_speed' => $this->integer(2)->defaultValue(0),
            'base_school_level' => $this->integer(2)->defaultValue(0),
            'base_school_player_count' => $this->integer(1)->defaultValue(0),
            'base_school_power' => $this->integer(2)->defaultValue(0),
            'base_school_price_buy' => $this->integer(7)->defaultValue(0),
            'base_school_price_sell' => $this->integer(7)->defaultValue(0),
            'base_school_school_speed' => $this->integer(2)->defaultValue(0),
            'base_school_with_special' => $this->integer(1)->defaultValue(0),
            'base_school_with_style' => $this->integer(1)->defaultValue(0),
        ]);

        $this->batchInsert(self::TABLE, [
            'base_school_base_level',
            'base_school_build_speed',
            'base_school_level',
            'base_school_player_count',
            'base_school_power',
            'base_school_price_buy',
            'base_school_price_sell',
            'base_school_school_speed',
            'base_school_with_special',
            'base_school_with_style'
        ], [
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
