<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000033Position
 * @package console\migrations
 */
class M200101000033Position extends Migration
{
    private const TABLE = '{{%position}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(2),
                'name' => $this->string(2)->notNull()->unique(),
                'text' => $this->string(255)->notNull()->unique(),
            ]
        );

        $this->batchInsert(
            self::TABLE,
            ['name', 'text'],
            [
                ['PR', 'P'],
                ['H', 'Hooker'],
                ['L', 'Lock'],
                ['FL', 'Flanker'],
                ['8', 'Number Eight'],
                ['SH', 'Scrum-half'],
                ['FH', 'Fly-half'],
                ['W', 'Wing'],
                ['C', 'Centre'],
                ['FB', 'Full-back'],
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
