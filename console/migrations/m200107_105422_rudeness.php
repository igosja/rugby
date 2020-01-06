<?php

use yii\db\Migration;

/**
 * Class m200107_105422_rudeness
 */
class m200107_105422_rudeness extends Migration
{
    const TABLE = '{{%rudeness}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'rudeness_id' => $this->primaryKey(1),
            'rudeness_name' => $this->string(10),
        ]);

        $this->batchInsert(self::TABLE, ['rudeness_name'], [
            ['нормальная'],
            ['грубая'],
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
