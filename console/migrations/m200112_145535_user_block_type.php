<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200112_145535_user_block_type
 * @package console\migrations
 */
class m200112_145535_user_block_type extends Migration
{
    private const TABLE = '{{%user_block_type}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_block_type_id' => $this->primaryKey(11),
            'user_block_type_text' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['user_block_type_text'], [
            ['Site'],
            ['Chat'],
            ['Comment'],
            ['Comment deal'],
            ['Comment game'],
            ['Comment news'],
            ['Forum'],
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
