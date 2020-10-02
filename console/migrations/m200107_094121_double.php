<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_094121_double
 * @package console\migrations
 */
class m200107_094121_double extends Migration
{
    private const TABLE = '{{%cookie}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'cookie_id' => $this->primaryKey(11),
            'cookie_child_id' => $this->integer(11)->defaultValue(0),
            'cookie_count' => $this->integer(3)->defaultValue(0),
            'cookie_date' => $this->integer(11)->defaultValue(0),
            'cookie_parent_id' => $this->integer(11)->defaultValue(0),
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
