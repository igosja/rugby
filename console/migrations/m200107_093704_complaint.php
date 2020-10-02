<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_093704_complaint
 * @package console\migrations
 */
class m200107_093704_complaint extends Migration
{
    private const TABLE = '{{%complaint}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'complaint_id' => $this->primaryKey(11),
            'complaint_date' => $this->integer(11)->defaultValue(0),
            'complaint_chat_id' => $this->integer(11)->defaultValue(0),
            'complaint_forum_message_id' => $this->integer(11)->defaultValue(0),
            'complaint_game_comment_id' => $this->integer(11)->defaultValue(0),
            'complaint_loan_comment_id' => $this->integer(11)->defaultValue(0),
            'complaint_news_comment_id' => $this->integer(11)->defaultValue(0),
            'complaint_ready' => $this->integer(11)->defaultValue(0),
            'complaint_transfer_comment_id' => $this->integer(11)->defaultValue(0),
            'complaint_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('complaint_ready', self::TABLE, 'complaint_ready');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
