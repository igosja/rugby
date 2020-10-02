<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_095152_event
 * @package console\migrations
 */
class m200107_095152_event extends Migration
{
    private const TABLE = '{{%event}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'event_id' => $this->primaryKey(11),
            'event_event_text_goal_id' => $this->integer(1)->defaultValue(0),
            'event_event_type_id' => $this->integer(1)->defaultValue(0),
            'event_game_id' => $this->integer(11)->defaultValue(0),
            'event_guest_score' => $this->integer(2)->defaultValue(0),
            'event_home_score' => $this->integer(2)->defaultValue(0),
            'event_minute' => $this->integer(2)->defaultValue(0),
            'event_national_id' => $this->integer(5)->defaultValue(0),
            'event_player_score_id' => $this->integer(11)->defaultValue(0),
            'event_team_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('event_game_id', self::TABLE, 'event_game_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
