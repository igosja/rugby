<?php

use yii\db\Migration;

/**
 * Class m200107_103246_money
 */
class m200107_103246_money extends Migration
{
    const TABLE = '{{%money}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'money_id' => $this->primaryKey(11),
            'money_date' => $this->integer(11)->defaultValue(0),
            'money_money_text_id' => $this->integer(2)->defaultValue(0),
            'money_user_id' => $this->integer(1)->defaultValue(0),
            'money_value' => $this->decimal(11, 2)->defaultValue(0),
            'money_value_after' => $this->decimal(11, 2)->defaultValue(0),
            'money_value_before' => $this->decimal(11, 2)->defaultValue(0),
        ]);

        $this->createIndex('money_user_id', self::TABLE, 'money_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
