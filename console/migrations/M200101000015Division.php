<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000015Division
 * @package console\migrations
 */
class M200101000015Division extends Migration
{
    private const TABLE = '{{%division}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->char(2)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['D1'],
                ['D2'],
                ['D3'],
                ['D4'],
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
