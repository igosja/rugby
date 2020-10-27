<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000054DealReason
 * @package console\migrations
 */
class M200101000054DealReason extends Migration
{
    private const TABLE = '{{%deal_reason}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['text'],
            [
                ['Лимит на одну сделку между менеджерами за сезон'],
                ['Лимит на одну сделку между командами за сезон'],
                ['У команды не хватило денег'],
                ['Не лучшая заявка'],
                ['Запрет на сделки между подопечными'],
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
