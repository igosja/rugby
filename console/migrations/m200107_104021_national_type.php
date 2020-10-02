<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104021_national_type
 * @package console\migrations
 */
class m200107_104021_national_type extends Migration
{
    private const TABLE = '{{%national_type}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'national_type_id' => $this->primaryKey(1),
            'national_type_name' => $this->string(15),
        ]);

        $this->batchInsert(self::TABLE, ['national_type_name'], [
            ['Национальная'],
            ['U-21'],
            ['U-19'],
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
