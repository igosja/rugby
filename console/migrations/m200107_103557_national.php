<?php

use yii\db\Migration;

/**
 * Class m200107_103557_national
 */
class m200107_103557_national extends Migration
{
    const TABLE = '{{%national}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'national_id' => $this->primaryKey(3),
            'national_country_id' => $this->integer(3)->defaultValue(0),
            'national_finance' => $this->integer(11)->defaultValue(0),
            'national_mood_rest' => $this->integer(1)->defaultValue(0),
            'national_mood_super' => $this->integer(1)->defaultValue(0),
            'national_national_type_id' => $this->integer(1)->defaultValue(0),
            'national_power_c_21' => $this->integer(5)->defaultValue(0),
            'national_power_c_26' => $this->integer(5)->defaultValue(0),
            'national_power_c_32' => $this->integer(5)->defaultValue(0),
            'national_power_s_21' => $this->integer(5)->defaultValue(0),
            'national_power_s_26' => $this->integer(5)->defaultValue(0),
            'national_power_s_32' => $this->integer(5)->defaultValue(0),
            'national_power_v' => $this->integer(5)->defaultValue(0),
            'national_power_vs' => $this->integer(5)->defaultValue(0),
            'national_stadium_id' => $this->integer(11)->defaultValue(0),
            'national_user_id' => $this->integer(11)->defaultValue(0),
            'national_vice_id' => $this->integer(11)->defaultValue(0),
            'national_visitor' => $this->integer(3)->defaultValue(0),
        ]);

        $this->createIndex('national_country_id', self::TABLE, 'national_country_id');
        $this->createIndex('national_national_type_id', self::TABLE, 'national_national_type_id');

        $this->batchInsert(self::TABLE, ['national_country_id', 'national_national_type_id'], [
            [7, 1],
            [7, 2],
            [7, 3],
            [9, 1],
            [9, 2],
            [9, 3],
            [54, 1],
            [54, 2],
            [54, 3],
            [61, 1],
            [61, 2],
            [61, 3],
            [81, 1],
            [81, 2],
            [81, 3],
            [83, 1],
            [83, 2],
            [83, 3],
            [124, 1],
            [124, 2],
            [124, 3],
            [154, 1],
            [154, 2],
            [154, 3],
            [164, 1],
            [164, 2],
            [164, 3],
            [194, 1],
            [194, 2],
            [194, 3],
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
