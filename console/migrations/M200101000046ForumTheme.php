<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000046ForumTheme
 * @package console\migrations
 */
class M200101000046ForumTheme extends Migration
{
    private const TABLE = '{{%forum_theme}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'count_view' => $this->integer(11)->defaultValue(0),
                'date' => $this->integer(11)->defaultValue(0),
                'date_update' => $this->integer(11)->defaultValue(0),
                'forum_group_id' => $this->integer(11)->notNull(),
                'name' => $this->string(255)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('forum_theme_forum_group_id', self::TABLE, 'forum_group_id', '{{%forum_group}}', 'id');
        $this->addForeignKey('forum_theme_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
