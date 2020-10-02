<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_111730_training
 * @package console\migrations
 */
class m200107_111730_training extends Migration
{
    private const TABLE = '{{%training}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'training_id' => $this->primaryKey(11),
            'training_percent' => $this->integer(3)->defaultValue(0),
            'training_player_id' => $this->integer(11)->defaultValue(0),
            'training_position_id' => $this->integer(1)->defaultValue(0),
            'training_power' => $this->integer(1)->defaultValue(0),
            'training_ready' => $this->integer(11)->defaultValue(0),
            'training_season_id' => $this->integer(3)->defaultValue(0),
            'training_special_id' => $this->integer(2)->defaultValue(0),
            'training_team_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('training_ready', self::TABLE, 'training_ready');
        $this->createIndex('training_season_id', self::TABLE, 'training_season_id');
        $this->createIndex('training_team_id', self::TABLE, 'training_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
