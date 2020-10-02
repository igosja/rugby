<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104140_news_comment
 * @package console\migrations
 */
class m200107_104140_news_comment extends Migration
{
    private const TABLE = '{{%news_comment}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'news_comment_id' => $this->primaryKey(11),
            'news_comment_check' => $this->integer(11)->defaultValue(0),
            'news_comment_date' => $this->integer(11)->defaultValue(0),
            'news_comment_news_id' => $this->integer(11)->defaultValue(0),
            'news_comment_text' => $this->text(),
            'news_comment_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('news_comment_check', self::TABLE, 'news_comment_check');
        $this->createIndex('news_comment_news_id', self::TABLE, 'news_comment_news_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
