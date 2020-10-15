<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000038Building
 * @package console\migrations
 */
class M200101000038Building extends Migration
{
    private const TABLE = '{{%building}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(255)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['base'],
                ['base_medical'],
                ['base_physical'],
                ['base_school'],
                ['base_scout'],
                ['base_training'],
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
