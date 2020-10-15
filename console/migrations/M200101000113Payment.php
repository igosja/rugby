<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000113Payment
 * @package console\migrations
 */
class M200101000113Payment extends Migration
{
    private const TABLE = '{{%payment}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'date' => $this->integer(11)->notNull(),
                'log' => $this->text(),
                'status' => $this->boolean()->defaultValue(false),
                'sum' => $this->decimal(11, 2)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('payment_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
