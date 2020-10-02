<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200106_155038_attitude
 * @package console\migrations
 */
class m200106_155038_attitude extends Migration
{
    private const TABLE = '{{%attitude}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(1),
            'name' => $this->string(255)->notNull(),
            'order' => $this->integer(1)->notNull(),
        ]);

        $this->batchInsert(self::TABLE, ['name', 'order'], [
            ['Negative', 3],
            ['Neutral', 2],
            ['Positive', 1],
        ]);

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
