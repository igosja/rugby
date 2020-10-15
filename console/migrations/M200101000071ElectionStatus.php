<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000071ElectionStatus
 * @package console\migrations
 */
class M200101000071ElectionStatus extends Migration
{
    private const TABLE = '{{%election_status}}';

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
                ['Сбор кандидатур'],
                ['Идет голосование'],
                ['Закрыто'],
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
