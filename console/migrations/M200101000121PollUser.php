<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000121PollUser
 * @package console\migrations
 */
class M200101000121PollUser extends Migration
{
    private const TABLE = '{{%poll_user}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'date' => $this->integer(11)->defaultValue(0),
                'poll_answer_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('poll_user_poll_answer_id', self::TABLE, 'poll_answer_id', '{{%poll_answer}}', 'id');
        $this->addForeignKey('poll_user_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
