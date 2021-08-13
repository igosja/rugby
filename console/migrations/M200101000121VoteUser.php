<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000121VoteUser
 * @package console\migrations
 */
class M200101000121VoteUser extends Migration
{
    private const TABLE = '{{%vote_user}}';

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
                'user_id' => $this->integer(11)->notNull(),
                'vote_answer_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('vote_user_vote_answer_id', self::TABLE, 'vote_answer_id', '{{%vote_answer}}', 'id');
        $this->addForeignKey('vote_user_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
