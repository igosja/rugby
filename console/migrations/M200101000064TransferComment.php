<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000064TransferComment
 * @package console\migrations
 */
class M200101000064TransferComment extends Migration
{
    private const TABLE = '{{%transfer_comment}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'check' => $this->integer(1)->defaultValue(0),
                'date' => $this->integer(11)->notNull(),
                'transfer_id' => $this->integer(11)->notNull(),
                'text' => $this->text()->notNull(),
                'user_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('transfer_comment_transfer_id', self::TABLE, 'transfer_id', '{{%transfer}}', 'id');
        $this->addForeignKey('transfer_comment_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

        $this->createIndex('check', self::TABLE, 'check');

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown():bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
