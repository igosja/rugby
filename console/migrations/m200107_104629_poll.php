<?php

use yii\db\Migration;

/**
 * Class m200107_104629_poll
 */
class m200107_104629_poll extends Migration
{
    const TABLE = '{{%poll}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'poll_id' => $this->primaryKey(11),
            'poll_country_id' => $this->integer(3)->defaultValue(0),
            'poll_date' => $this->integer(11)->defaultValue(0),
            'poll_text' => $this->text(),
            'poll_user_id' => $this->integer(11)->defaultValue(0),
            'poll_poll_status_id' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex('poll_country_id', self::TABLE, 'poll_country_id');
        $this->createIndex('poll_poll_status_id', self::TABLE, 'poll_poll_status_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
