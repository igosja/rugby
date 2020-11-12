<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000133StatisticChapter
 * @package console\migrations
 */
class M200101000133StatisticChapter extends Migration
{
    private const TABLE = '{{%statistic_chapter}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(10)->notNull()->unique(),
                'order' => $this->integer(1)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'order'],
            [
                ['Teams', 1],
                ['Players', 2],
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
