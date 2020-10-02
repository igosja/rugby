<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111438_tactic
 * @package console\migrations
 */
class m200107_111438_tactic extends Migration
{
    private const TABLE = '{{%tactic}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'tactic_id' => $this->primaryKey(1),
            'tactic_name' => $this->string(20),
        ]);

        $this->batchInsert(self::TABLE, ['tactic_name'], [
            ['суперзащитная'],
            ['защитная'],
            ['номальная'],
            ['атакующая'],
            ['суператакующая'],
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
