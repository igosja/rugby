<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000118VoteStatus
 * @package console\migrations
 */
class M200101000118VoteStatus extends Migration
{
    private const TABLE = '{{%vote_status}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(25)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['Ожидает проверки'],
                ['Открыто'],
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
