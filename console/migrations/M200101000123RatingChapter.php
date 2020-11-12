<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000123RatingChapter
 * @package console\migrations
 */
class M200101000123RatingChapter extends Migration
{
    private const TABLE = '{{%rating_chapter}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(255)->notNull()->unique(),
                'order' => $this->integer(1)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'order'],
            [
                ['Команды', 1],
                ['Менеджеры', 2],
                ['Страны', 3],
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
