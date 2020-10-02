<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104718_poll_status
 * @package console\migrations
 */
class m200107_104718_poll_status extends Migration
{
    private const TABLE = '{{%poll_status}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'poll_status_id' => $this->primaryKey(1),
            'poll_status_name' => $this->string(25),
        ]);

        $this->batchInsert(self::TABLE, ['poll_status_name'], [
            ['Ожидает проверки'],
            ['Открыто'],
            ['Закрыто'],
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
