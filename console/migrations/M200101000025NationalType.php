<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000025NationalType
 * @package console\migrations
 */
class M200101000025NationalType extends Migration
{
    private const TABLE = '{{%national_type}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(1),
                'name' => $this->string(15)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name'],
            [
                ['National'],
                ['U-21'],
                ['U-19'],
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
