<?php

use yii\db\Migration;

/**
 * Class m200107_104746_poll_user
 */
class m200107_104746_poll_user extends Migration
{
    const TABLE = '{{%poll_user}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'poll_user_date' => $this->integer(11)->defaultValue(0),
            'poll_user_poll_answer_id' => $this->integer(11)->defaultValue(0),
            'poll_user_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('poll_user_poll_answer_id', self::TABLE, 'poll_user_poll_answer_id');
        $this->createIndex('poll_user_user_id', self::TABLE, 'poll_user_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
