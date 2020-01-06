<?php

use yii\db\Migration;

/**
 * Class m200112_152731_user_login
 */
class m200112_152731_user_login extends Migration
{
    const TABLE = '{{%user_login}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_login_id' => $this->primaryKey(11),
            'user_login_agent' => $this->string(255),
            'user_login_ip' => $this->string(255),
            'user_login_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('user_login_user_id', self::TABLE, 'user_login_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
