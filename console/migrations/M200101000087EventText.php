<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000087EventText
 * @package console\migrations
 */
class M200101000087EventText extends Migration
{
    private const TABLE = '{{%event_text}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'event_type_id' => $this->integer(1)->notNull(),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->addForeignKey('event_text_event_type_id', self::TABLE, 'event_type_id', '{{%event_type}}', 'id');

        $this->batchInsert(
            self::TABLE,
            ['event_type_id', 'text'],
            [
                [1, 'Try'],
                [1, 'Conversion'],
                [1, 'Penalty'],
                [1, 'Drop Goal'],
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
