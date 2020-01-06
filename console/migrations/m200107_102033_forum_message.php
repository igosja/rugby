<?php

use yii\db\Migration;

/**
 * Class m200107_102033_forum_message
 */
class m200107_102033_forum_message extends Migration
{
    const TABLE = '{{%forum_message}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'forum_message_id' => $this->primaryKey(11),
            'forum_message_blocked' => $this->integer(11)->defaultValue(0),
            'forum_message_check' => $this->integer(11)->defaultValue(0),
            'forum_message_date' => $this->integer(11)->defaultValue(0),
            'forum_message_date_update' => $this->integer(11)->defaultValue(0),
            'forum_message_forum_theme_id' => $this->integer(11)->defaultValue(0),
            'forum_message_text' => $this->text(),
            'forum_message_user_id' => $this->string(255),
        ]);

        $this->createIndex('forum_message_check', self::TABLE, 'forum_message_check');
        $this->createIndex('forum_message_forum_theme_id', self::TABLE, 'forum_message_forum_theme_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
