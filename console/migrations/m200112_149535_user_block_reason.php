<?php

use yii\db\Migration;

/**
 * Class m200112_149535_user_block_reason
 */
class m200112_149535_user_block_reason extends Migration
{
    const TABLE = '{{%user_block_reason}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_block_reason_id' => $this->primaryKey(2),
            'user_block_reason_text' => $this->string(255),
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
