<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210903102314Test
 */
class M210903102314Test extends Migration
{
    private const TABLE = '{{%test}}';

    /**
     * @return bool
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'value' => $this->string(),
        ]);

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210903102314Test cannot be reverted.\n";

        return false;
    }
    */
}
