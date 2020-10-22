<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000068Complaint
 * @package console\migrations
 */
class M200101000068Complaint extends Migration
{
    private const TABLE = '{{%complaint}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->notNull(),
                'chat_id' => $this->integer(11),
                'forum_message_id' => $this->integer(11),
                'game_comment_id' => $this->integer(11),
                'loan_comment_id' => $this->integer(11),
                'news_id' => $this->integer(11),
                'news_comment_id' => $this->integer(11),
                'ready' => $this->integer(11),
                'text' => $this->text()->notNull(),
                'transfer_comment_id' => $this->integer(11),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('complaint_chat_id', self::TABLE, 'chat_id', '{{%chat}}', 'id');
        $this->addForeignKey('complaint_forum_message_id', self::TABLE, 'forum_message_id', '{{%forum_message}}', 'id');
        $this->addForeignKey('complaint_game_comment_id', self::TABLE, 'game_comment_id', '{{%game_comment}}', 'id');
        $this->addForeignKey('complaint_loan_comment_id', self::TABLE, 'loan_comment_id', '{{%loan_comment}}', 'id');
        $this->addForeignKey('complaint_news_id', self::TABLE, 'news_id', '{{%news}}', 'id');
        $this->addForeignKey('complaint_news_comment_id', self::TABLE, 'news_comment_id', '{{%news_comment}}', 'id');
        $this->addForeignKey(
            'complaint_transfer_comment_id',
            self::TABLE,
            'transfer_comment_id',
            '{{%transfer_comment}}',
            'id'
        );
        $this->addForeignKey('complaint_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
