<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_093349_building_base
 * @package console\migrations
 */
class m200107_093349_building_base extends Migration
{
    private const TABLE = '{{%building_base}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'building_base_id' => $this->primaryKey(11),
            'building_base_building_id' => $this->integer(11)->defaultValue(0),
            'building_base_construction_type_id' => $this->integer(1)->defaultValue(0),
            'building_base_date' => $this->integer(11)->defaultValue(0),
            'building_base_day' => $this->integer(2)->defaultValue(0),
            'building_base_ready' => $this->integer(11)->defaultValue(0),
            'building_base_team_id' => $this->integer(5)->defaultValue(0),
        ]);

        $this->createIndex('building_base_ready', self::TABLE, 'building_base_ready');
        $this->createIndex('building_base_team_id', self::TABLE, 'building_base_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
