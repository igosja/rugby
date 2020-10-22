<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000061NewsComment
 * @package console\migrations
 */
class M200101000061NewsComment extends Migration
{
    private const TABLE = '{{%news_comment}}';

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
                'news_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('news_comment_news_id', self::TABLE, 'news_id', '{{%news}}', 'id');
        $this->addForeignKey('news_comment_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
