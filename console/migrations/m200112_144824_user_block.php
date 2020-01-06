<?php

use yii\db\Migration;

/**
 * Class m200112_144824_user_block
 */
class m200112_144824_user_block extends Migration
{
    const TABLE = '{{%user_block}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_block_id' => $this->primaryKey(11),
            'user_block_date' => $this->integer(11)->defaultValue(0),
            'user_block_user_block_reason_id' => $this->integer(2)->defaultValue(0),
            'user_block_user_block_type_id' => $this->integer(1)->defaultValue(0),
            'user_block_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('user_block_user_id', self::TABLE, 'user_block_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
