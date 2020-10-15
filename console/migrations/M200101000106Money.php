<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000106Money
 * @package console\migrations
 */
class M200101000106Money extends Migration
{
    private const TABLE = '{{%money}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->defaultValue(0),
                'money_text_id' => $this->integer(2)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
                'value' => $this->decimal(11, 2)->notNull(),
                'value_after' => $this->decimal(11, 2)->notNull(),
                'value_before' => $this->decimal(11, 2)->notNull(),
            ]
        );

        $this->addForeignKey('money_money_text_id', self::TABLE, 'money_text_id', '{{%money_text}}', 'id');
        $this->addForeignKey('money_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
