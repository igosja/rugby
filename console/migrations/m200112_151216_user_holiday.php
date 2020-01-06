<?php

use yii\db\Migration;

/**
 * Class m200112_151216_user_holiday
 */
class m200112_151216_user_holiday extends Migration
{
    const TABLE = '{{%user_holiday}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_holiday_id' => $this->primaryKey(11),
            'user_holiday_date_end' => $this->integer(11)->defaultValue(0),
            'user_holiday_date_start' => $this->integer(11)->defaultValue(0),
            'user_holiday_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('user_holiday_user_id', self::TABLE, 'user_holiday_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
