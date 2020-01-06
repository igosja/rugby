<?php

use yii\db\Migration;

/**
 * Class m200107_110251_statistic_chapter
 */
class m200107_110251_statistic_chapter extends Migration
{
    const TABLE = '{{%statistic_chapter}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'statistic_chapter_id' => $this->primaryKey(1),
            'statistic_chapter_name' => $this->string(10),
            'statistic_chapter_order' => $this->integer(1),
        ]);

        $this->batchInsert(self::TABLE, ['statistic_chapter_name', 'statistic_chapter_order'], [
            ['Команды', 1],
            ['Игроки', 2],
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
