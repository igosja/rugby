<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000120PollAnswer
 * @package console\migrations
 */
class M200101000120PollAnswer extends Migration
{
    private const TABLE = '{{%poll_answer}}';

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
                'poll_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('poll_answer_poll_id', self::TABLE, 'poll_id', '{{%poll}}', 'id');

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
