<?php

use yii\db\Migration;

/**
 * Class m200106_155655_base_training
 */
class m200106_155655_base_training extends Migration
{
    const TABLE = '{{%base_training}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'base_training_id' => $this->primaryKey(2),
            'base_training_base_level' => $this->integer(1)->defaultValue(0),
            'base_training_build_speed' => $this->integer(2)->defaultValue(0),
            'base_training_level' => $this->integer(2)->defaultValue(0),
            'base_training_position_count' => $this->integer(1)->defaultValue(0),
            'base_training_position_price' => $this->integer(6)->defaultValue(0),
            'base_training_power_count' => $this->integer(2)->defaultValue(0),
            'base_training_power_price' => $this->integer(6)->defaultValue(0),
            'base_training_price_buy' => $this->integer(7)->defaultValue(0),
            'base_training_price_sell' => $this->integer(7)->defaultValue(0),
            'base_training_special_count' => $this->integer(1)->defaultValue(0),
            'base_training_special_price' => $this->integer(6)->defaultValue(0),
            'base_training_training_speed_max' => $this->integer(3)->defaultValue(0),
            'base_training_training_speed_min' => $this->integer(3)->defaultValue(0),
        ]);

        $this->batchInsert(self::TABLE, [
            'base_training_base_level',
            'base_training_build_speed',
            'base_training_level',
            'base_training_position_count',
            'base_training_position_price',
            'base_training_power_count',
            'base_training_power_price',
            'base_training_price_buy',
            'base_training_price_sell',
            'base_training_special_count',
            'base_training_special_price',
            'base_training_training_speed_max',
            'base_training_training_speed_min'
        ], [
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [1, 1, 1, 1, 50000, 5, 25000, 250000, 187500, 1, 50000, 15, 5],
            [1, 2, 2, 1, 100000, 10, 50000, 500000, 375000, 1, 100000, 25, 15],
            [2, 3, 3, 2, 150000, 15, 75000, 750000, 562500, 3, 150000, 35, 25],
            [2, 4, 4, 2, 200000, 20, 100000, 1000000, 750000, 4, 200000, 45, 35],
            [3, 5, 5, 3, 250000, 25, 125000, 1250000, 937500, 5, 250000, 55, 45],
            [3, 6, 6, 3, 300000, 30, 150000, 1500000, 1125000, 6, 300000, 65, 55],
            [4, 7, 7, 4, 350000, 35, 175000, 1750000, 1312500, 7, 350000, 75, 65],
            [4, 8, 8, 4, 400000, 40, 200000, 2000000, 1500000, 8, 400000, 85, 75],
            [5, 9, 9, 5, 450000, 45, 225000, 2250000, 1687500, 9, 450000, 95, 85],
            [5, 10, 10, 5, 500000, 50, 250000, 2500000, 1875000, 10, 500000, 100, 100],
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
