<?php

use yii\db\Migration;

/**
 * Class m200107_101848_fire_reason
 */
class m200107_101848_fire_reason extends Migration
{
    const TABLE = '{{%fire_reason}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'fire_reason_id' => $this->primaryKey(2),
            'fire_reason_text' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['fire_reason_text'], [
            ['self'],
            ['auto'],
            ['absence'],
            ['penalty'],
            ['extra team'],
            ['vote'],
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
