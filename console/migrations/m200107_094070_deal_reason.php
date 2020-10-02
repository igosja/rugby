<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_094070_deal_reason
 * @package console\migrations
 */
class m200107_094070_deal_reason extends Migration
{
    private const TABLE = '{{%deal_reason}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'deal_reason_id' => $this->primaryKey(2),
            'deal_reason_text' => $this->string(255)
        ]);

        $this->batchInsert(self::TABLE, ['deal_reason_text'], [
            ['Лимит на одну сделку между менеджерами за сезон'],
            ['Лимит на одну сделку между командами за сезон'],
            ['У команды не хватило денег'],
            ['Не лучшая заявка'],
            ['Запрет на сделки между подопечными'],
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
