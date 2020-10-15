<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000066TransferSpecial
 * @package console\migrations
 */
class M200101000066TransferSpecial extends Migration
{
    private const TABLE = '{{%transfer_special}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'level' => $this->integer(1)->notNull(),
                'special_id' => $this->integer(2)->notNull(),
                'transfer_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('transfer_special_transfer_id', self::TABLE, 'transfer_id', '{{%transfer}}', 'id');
        $this->addForeignKey('transfer_special_special_id', self::TABLE, 'special_id', '{{%special}}', 'id');

        $this->createIndex('transfer_special', self::TABLE, ['transfer_id', 'special_id'], true);

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
