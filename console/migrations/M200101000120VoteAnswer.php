<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000120VoteAnswer
 * @package console\migrations
 */
class M200101000120VoteAnswer extends Migration
{
    private const TABLE = '{{%vote_answer}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'text' => $this->text()->notNull(),
                'vote_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('vote_answer_vote_id', self::TABLE, 'vote_id', '{{%vote}}', 'id');

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
