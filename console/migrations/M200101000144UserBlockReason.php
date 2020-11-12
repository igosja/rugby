<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000144UserBlockReason
 * @package console\migrations
 */
class M200101000144UserBlockReason extends Migration
{
    private const TABLE = '{{%user_block_reason}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'text' => $this->string(255)->notNull()->unique(),
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
