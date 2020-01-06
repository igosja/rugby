<?php

use yii\db\Migration;

/**
 * Class m200106_155038_attitude
 */
class m200106_155038_attitude extends Migration
{
    const TABLE = '{{%attitude}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'attitude_id' => $this->primaryKey(1),
            'attitude_name' => $this->string(255),
            'attitude_order' => $this->integer(1)->defaultValue(0),
        ]);

        $this->batchInsert(self::TABLE, ['attitude_name', 'attitude_order'], [
            ['Negative', 3],
            ['Neutral', 2],
            ['Positive', 1],
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
