<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000137Support
 * @package console\migrations
 */
class M200101000137Support extends Migration
{
    private const TABLE = '{{%support}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'admin_id' => $this->integer(11),
                'date' => $this->integer(11)->defaultValue(0),
                'federation_id' => $this->integer(3),
                'is_inside' => $this->boolean()->defaultValue(false),
                'is_question' => $this->integer(1)->defaultValue(1),
                'president_user_id' => $this->integer(11)->defaultValue(1),
                'read' => $this->integer(11)->defaultValue(0),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11),
            ]
        );

        $this->addForeignKey('support_admin_id', self::TABLE, 'admin_id', '{{%user}}', 'id');
        $this->addForeignKey('support_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('support_president_user_id', self::TABLE, 'president_user_id', '{{%user}}', 'id');
        $this->addForeignKey('support_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('read', self::TABLE, 'read');

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
