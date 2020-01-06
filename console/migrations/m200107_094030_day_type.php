<?php

use yii\db\Migration;

/**
 * Class m200107_094030_day_type
 */
class m200107_094030_day_type extends Migration
{
    const TABLE = '{{%day_type}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'day_type_id' => $this->primaryKey(1),
            'day_type_name' => $this->char(1),
            'day_type_text' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['day_type_name', 'day_type_text'], [
            ['A', 'Friendly'],
            ['B', 'Required games'],
            ['C', 'Additional games'],
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
