<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000044ForumChapter
 * @package console\migrations
 */
class M200101000044ForumChapter extends Migration
{
    private const TABLE = '{{%forum_chapter}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'name' => $this->string(255)->notNull(),
                'order' => $this->integer(1)->notNull(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'order'],
            [
                ['Общие', 1],
                ['Сделки и договоры', 2],
                ['За пределами Лиги', 3],
                ['Национальные форумы', 4],
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
