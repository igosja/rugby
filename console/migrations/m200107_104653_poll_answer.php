<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104653_poll_answer
 * @package console\migrations
 */
class m200107_104653_poll_answer extends Migration
{
    private const TABLE = '{{%poll_answer}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'poll_answer_id' => $this->primaryKey(11),
            'poll_answer_text' => $this->text(),
            'poll_answer_poll_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('poll_answer_poll_id', self::TABLE, 'poll_answer_poll_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
