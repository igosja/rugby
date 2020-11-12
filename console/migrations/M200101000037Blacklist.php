<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000037Blacklist
 * @package console\migrations
 */
class M200101000037Blacklist extends Migration
{
    private const TABLE = '{{%blacklist}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'blocked_user_id' => $this->integer(11)->notNull(),
                'owner_user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('blacklist_blocked_user_id', self::TABLE, 'blocked_user_id', '{{%user}}', 'id');
        $this->addForeignKey('blacklist_owner_user_id', self::TABLE, 'owner_user_id', '{{%user}}', 'id');

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
