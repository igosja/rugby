<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000017Sex
 * @package console\migrations
 */
class M200101000017Sex extends Migration
{
    private const TABLE = '{{%sex}}';

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
                ['male'],
                ['female'],
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
