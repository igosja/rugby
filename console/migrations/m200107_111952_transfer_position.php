<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111952_transfer_position
 * @package console\migrations
 */
class m200107_111952_transfer_position extends Migration
{
    private const TABLE = '{{%transfer_position}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'transfer_position_id' => $this->primaryKey(11),
            'transfer_position_position_id' => $this->integer(1)->defaultValue(0),
            'transfer_position_transfer_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('transfer_position_transfer_id', self::TABLE, 'transfer_position_transfer_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
