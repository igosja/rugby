<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000143UserBlockType
 * @package console\migrations
 */
class M200101000143UserBlockType extends Migration
{
    private const TABLE = '{{%user_block_type}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['text'],
            [
                ['Site'],
                ['Chat'],
                ['Comment'],
                ['Comment deal'],
                ['Comment game'],
                ['Comment news'],
                ['Forum'],
            ]
        );

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
