<?php

use yii\db\Migration;

/**
 * Class m200107_104326_payment
 */
class m200107_104326_payment extends Migration
{
    const TABLE = '{{%payment}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'payment_id' => $this->primaryKey(11),
            'payment_date' => $this->integer(11)->defaultValue(0),
            'payment_log' => $this->text(),
            'payment_status' => $this->integer(1)->defaultValue(0),
            'payment_sum' => $this->decimal(11, 2)->defaultValue(0),
            'payment_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('payment_user_id', self::TABLE, 'payment_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
