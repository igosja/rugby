<?php

use yii\db\Migration;

/**
 * Class m200107_105100_rating_chapter
 */
class m200107_105100_rating_chapter extends Migration
{
    const TABLE = '{{%rating_chapter}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'rating_chapter_id' => $this->primaryKey(1),
            'rating_chapter_name' => $this->string(255),
            'rating_chapter_order' => $this->integer(1)->defaultValue(0),
        ]);

        $this->batchInsert(self::TABLE, ['rating_chapter_name', 'rating_chapter_order'], [
            ['Команды', 1],
            ['Менеджеры', 2],
            ['Страны', 3],
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
