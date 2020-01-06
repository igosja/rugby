<?php

use yii\db\Migration;

/**
 * Class m200107_102116_forum_theme
 */
class m200107_102116_forum_theme extends Migration
{
    const TABLE = '{{%forum_theme}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'forum_theme_id' => $this->primaryKey(11),
            'forum_theme_count_view' => $this->integer(11)->defaultValue(0),
            'forum_theme_date' => $this->integer(11)->defaultValue(0),
            'forum_theme_date_update' => $this->integer(11)->defaultValue(0),
            'forum_theme_forum_group_id' => $this->integer(11)->defaultValue(0),
            'forum_theme_name' => $this->string(255),
            'forum_theme_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('forum_theme_forum_group_id', self::TABLE, 'forum_theme_forum_group_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
