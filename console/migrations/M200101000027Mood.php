<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000027Mood
 * @package console\migrations
 */
class M200101000027Mood extends Migration
{
    private const TABLE = '{{%mood}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(10)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['super'],
                ['normal'],
                ['rest'],
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
