<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000001Season
 * @package console\migrations
 */
class M200101000001Season extends Migration
{
    private const TABLE = '{{%season}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(3),
                'is_future' => $this->boolean()->defaultValue(false),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['id', 'is_future'],
            [
                [1, false],
                [2, true],
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
