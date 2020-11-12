<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000021News
 * @package console\migrations
 */
class M200101000021News extends Migration
{
    private const TABLE = '{{%news}}';

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
                'federation_id' => $this->integer(3),
                'text' => $this->text()->notNull(),
                'title' => $this->string(255)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('news_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('news_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('user_news_id', '{{%user}}', 'news_id', self::TABLE, 'id');

        $this->createIndex('check', self::TABLE, 'check');

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropForeignKey('user_news_id', '{{%user}}');

        $this->dropTable(self::TABLE);

        return true;
    }
}
