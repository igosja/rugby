<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000086EventType
 * @package console\migrations
 */
class M200101000086EventType extends Migration
{
    private const TABLE = '{{%event_type}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'text' => $this->string(255)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['text'],
            [
                ['Score'],
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
