<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_093435_building_stadium
 * @package console\migrations
 */
class m200107_093435_building_stadium extends Migration
{
    private const TABLE = '{{%building_stadium}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'building_stadium_id' => $this->primaryKey(11),
            'building_stadium_capacity' => $this->integer(5)->defaultValue(0),
            'building_stadium_construction_type_id' => $this->integer(1)->defaultValue(0),
            'building_stadium_date' => $this->integer(11)->defaultValue(0),
            'building_stadium_day' => $this->integer(2)->defaultValue(0),
            'building_stadium_ready' => $this->integer(11)->defaultValue(0),
            'building_stadium_team_id' => $this->integer(5)->defaultValue(0),
        ]);

        $this->createIndex('building_stadium_ready', self::TABLE, 'building_stadium_ready');
        $this->createIndex('building_stadium_team_id', self::TABLE, 'building_stadium_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
