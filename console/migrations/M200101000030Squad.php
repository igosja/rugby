<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000030Squad
 * @package console\migrations
 */
class M200101000030Squad extends Migration
{
    private const TABLE = '{{%squad}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'color' => $this->char(6)->unique(),
                'name' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['color', 'name'],
            [
                [null, '------'],
                ['DFF2BF', '1 состав'],
                ['C9FFCC', '2 состав'],
                ['FEEFB3', '3 состав'],
                ['FFBABA', '4 состав'],
                ['E0E0E0', '5 состав'],
                ['E0E0E0', '6 состав'],
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
