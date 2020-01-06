<?php

use yii\db\Migration;

/**
 * Class m200107_104920_position
 */
class m200107_104920_position extends Migration
{
    const TABLE = '{{%position}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'position_id' => $this->primaryKey(1),
            'position_name' => $this->string(2),
            'position_text' => $this->string(255),
        ]);

        $this->batchInsert(self::TABLE, ['position_name', 'position_text'], [
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
        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
