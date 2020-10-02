<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_112017_transfer_special
 * @package console\migrations
 */
class m200107_112017_transfer_special extends Migration
{
    private const TABLE = '{{%transfer_special}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'transfer_special_id' => $this->primaryKey(11),
            'transfer_special_level' => $this->integer(1)->defaultValue(0),
            'transfer_special_transfer_id' => $this->integer(11)->defaultValue(0),
            'transfer_special_special_id' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('transfer_special_transfer_id', self::TABLE, 'transfer_special_transfer_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
