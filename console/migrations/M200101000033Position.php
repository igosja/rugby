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
//                ['1', 'Loosehead prop'],
//                ['2', 'Hooker'],
//                ['3', 'Tighthead prop'],
//                ['4', 'Number-4 lock'],
//                ['5', 'Number-5 lock'],
//                ['6', 'Blindside flanker'],
//                ['7', 'Openside flanker'],
//                ['8', 'Number 8'],
//                ['9', 'Scrum-half'],
//                ['10', 'Fly-half'],
//                ['11', 'Left-wing'],
//                ['12', 'Inside-centre'],
//                ['13', 'Outside-centre'],
//                ['14', 'Right-wing'],
//                ['15', 'Fullback'],
                ['PR', 'Prop'],//1
                ['H', 'Hooker'],//2
                ['L', 'Lock'],//3
                ['FL', 'Flanker'],//4
                ['8', 'Number Eight'],//5
                ['SH', 'Scrum-half'],//6
                ['FH', 'Fly-half'],//7
                ['W', 'Wing'],//8
                ['C', 'Centre'],//9
                ['FB', 'Full-back'],//10
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
