<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000049Tactic
 * @package console\migrations
 */
class M200101000049Tactic extends Migration
{
    private const TABLE = '{{%tactic}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(20)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['суперзащитная'],
                ['защитная'],
                ['номальная'],
                ['атакующая'],
                ['суператакующая'],
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
