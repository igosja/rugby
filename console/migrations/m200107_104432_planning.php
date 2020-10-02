<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104432_planning
 * @package console\migrations
 */
class m200107_104432_planning extends Migration
{
    private const TABLE = '{{%planning}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'planning_id' => $this->primaryKey(11),
            'planning_player_id' => $this->integer(11)->defaultValue(0),
            'planning_season_id' => $this->integer(3)->defaultValue(0),
            'planning_schedule_id' => $this->integer(11)->defaultValue(0),
            'planning_team_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('planning_player_id', self::TABLE, 'planning_player_id');
        $this->createIndex('planning_schedule_id', self::TABLE, 'planning_schedule_id');
        $this->createIndex('planning_team_id', self::TABLE, 'planning_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
