<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_104257_participant_league
 * @package console\migrations
 */
class m200107_104257_participant_league extends Migration
{
    private const TABLE = '{{%participant_league}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'participant_league_id' => $this->primaryKey(11),
            'participant_league_season_id' => $this->integer(3)->defaultValue(0),
            'participant_league_stage_1' => $this->integer(1)->defaultValue(0),
            'participant_league_stage_2' => $this->integer(1)->defaultValue(0),
            'participant_league_stage_4' => $this->integer(1)->defaultValue(0),
            'participant_league_stage_8' => $this->integer(1)->defaultValue(0),
            'participant_league_stage_id' => $this->integer(2)->defaultValue(0),
            'participant_league_stage_in' => $this->integer(2)->defaultValue(0),
            'participant_league_team_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('participant_league_season_id', self::TABLE, 'participant_league_season_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
