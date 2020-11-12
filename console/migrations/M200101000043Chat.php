<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000043Chat
 * @package console\migrations
 */
class M200101000043Chat extends Migration
{
    private const TABLE = '{{%chat}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'check' => $this->integer(11),
                'date' => $this->integer(11)->notNull(),
                'message' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('chat_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('chat_check', self::TABLE, 'check');

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
