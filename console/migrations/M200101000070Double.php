<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000070Double
 * @package console\migrations
 */
class M200101000070Double extends Migration
{
    private const TABLE = '{{%double}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'child_user_id' => $this->integer(11)->notNull(),
                'count' => $this->integer(3)->defaultValue(0),
                'date' => $this->integer(11)->notNull(),
                'parent_user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('double_child_user_id', self::TABLE, 'child_user_id', '{{%user}}', 'id');
        $this->addForeignKey('double_parent_user_id', self::TABLE, 'parent_user_id', '{{%user}}', 'id');

        $this->createIndex('double_user', self::TABLE, ['child_user_id', 'parent_user_id'], true);

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
