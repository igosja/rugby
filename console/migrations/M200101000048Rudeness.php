<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000048Rudeness
 * @package console\migrations
 */
class M200101000048Rudeness extends Migration
{
    private const TABLE = '{{%rudeness}}';

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
                ['нормальная'],
                ['грубая'],
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
