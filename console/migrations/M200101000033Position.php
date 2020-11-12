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
                ['1', 'Loosehead prop'],
                ['2', 'Hooker'],
                ['3', 'Tighthead prop'],
                ['4', 'Number-4 lock'],
                ['5', 'Number-5 lock'],
                ['6', 'Blindside flanker'],
                ['7', 'Openside flanker'],
                ['8', 'Number 8'],
                ['9', 'Scrum-half'],
                ['10', 'Fly-half'],
                ['11', 'Left-wing'],
                ['12', 'Inside-centre'],
                ['13', 'Outside-centre'],
                ['14', 'Right-wing'],
                ['15', 'Fullback'],
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
