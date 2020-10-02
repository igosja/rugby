<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000104Message
 * @package console\migrations
 */
class M200101000104Message extends Migration
{
    private const TABLE = '{{%message}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->defaultValue(0),
                'read' => $this->integer(11)->defaultValue(0),
                'text' => $this->text()->notNull(),
                'from_user_id' => $this->integer(11)->notNull(),
                'to_user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('message_from_user_id', self::TABLE, 'from_user_id', '{{%user}}', 'id');
        $this->addForeignKey('message_to_user_id', self::TABLE, 'to_user_id', '{{%user}}', 'id');

        $this->createIndex('read', self::TABLE, 'read');

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
