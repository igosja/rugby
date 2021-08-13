<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000067TransferVote
 * @package console\migrations
 */
class M200101000067TransferVote extends Migration
{
    private const TABLE = '{{%transfer_vote}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'rating' => $this->integer(2)->notNull(),
                'transfer_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('transfer_vote_transfer_id', self::TABLE, 'transfer_id', '{{%transfer}}', 'id');
        $this->addForeignKey('transfer_vote_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('transfer_user', self::TABLE, ['transfer_id', 'user_id'], true);

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
