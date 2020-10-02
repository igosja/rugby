<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000022FriendlyStatus
 * @package console\migrations
 */
class M200101000022FriendlyStatus extends Migration
{
    private const TABLE = '{{%friendly_status}}';

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
                ['Я принимаю любое приглашение'],
                ['Я самостоятельно выбираю соперников для моей команды'],
                ['Я не хочу принимать приглашения'],
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
