<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000023Attitude
 * @package console\migrations
 */
class M200101000023Attitude extends Migration
{
    private const TABLE = '{{%attitude}}';

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
                ['Negative', 3],
                ['Neutral', 2],
                ['Positive', 1],
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
