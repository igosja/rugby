<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000065TransferPosition
 * @package console\migrations
 */
class M200101000065TransferPosition extends Migration
{
    private const TABLE = '{{%transfer_position}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'position_id' => $this->integer(2)->defaultValue(0),
                'transfer_id' => $this->integer(11)->defaultValue(0),
            ]
        );

        $this->addForeignKey('transfer_position_position_id', self::TABLE, 'position_id', '{{%position}}', 'id');
        $this->addForeignKey('transfer_position_transfer_id', self::TABLE, 'transfer_id', '{{%transfer}}', 'id');

        $this->createIndex('transfer_position', self::TABLE, ['transfer_id', 'position_id'], true);

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
