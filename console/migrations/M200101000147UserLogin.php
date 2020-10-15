<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000147UserLogin
 * @package console\migrations
 */
class M200101000147UserLogin extends Migration
{
    private const TABLE = '{{%user_login}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'agent' => $this->string(255)->notNull(),
                'ip' => $this->string(255)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('user_login_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
