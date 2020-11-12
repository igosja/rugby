<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000091FireReason
 * @package console\migrations
 */
class M200101000091FireReason extends Migration
{
    private const TABLE = '{{%fire_reason}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['text'],
            [
                ['self'],
                ['auto'],
                ['absence'],
                ['penalty'],
                ['extra team'],
                ['vote'],
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
