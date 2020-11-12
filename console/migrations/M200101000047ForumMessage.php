<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000047ForumMessage
 * @package console\migrations
 */
class M200101000047ForumMessage extends Migration
{
    private const TABLE = '{{%forum_message}}';

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
                'date_blocked' => $this->integer(11),
                'date_update' => $this->integer(11),
                'forum_theme_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('forum_message_forum_theme_id', self::TABLE, 'forum_theme_id', '{{%forum_theme}}', 'id');
        $this->addForeignKey('forum_message_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('check', self::TABLE, 'check');

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
