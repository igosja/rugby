<?php

use yii\db\Migration;

/**
 * Class m200106_155335_base_medical
 */
class m200106_155335_base_medical extends Migration
{
    const TABLE = '{{%base_medical}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'base_medical_id' => $this->primaryKey(2),
            'base_medical_base_level' => $this->integer(1)->defaultValue(0),
            'base_medical_build_speed' => $this->integer(2)->defaultValue(0),
            'base_medical_level' => $this->integer(2)->defaultValue(0),
            'base_medical_price_buy' => $this->integer(7)->defaultValue(0),
            'base_medical_price_sell' => $this->integer(7)->defaultValue(0),
            'base_medical_tire' => $this->integer(2)->defaultValue(0),
        ]);

        $this->batchInsert(self::TABLE, [
            'base_medical_base_level',
            'base_medical_build_speed',
            'base_medical_level',
            'base_medical_price_buy',
            'base_medical_price_sell',
            'base_medical_tire'
        ], [
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
