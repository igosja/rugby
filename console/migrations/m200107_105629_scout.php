<?php

use yii\db\Migration;

/**
 * Class m200107_105629_scout
 */
class m200107_105629_scout extends Migration
{
    const TABLE = '{{%scout}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'scout_id' => $this->primaryKey(11),
            'scout_is_school' => $this->integer(1)->defaultValue(0),
            'scout_percent' => $this->integer(3)->defaultValue(0),
            'scout_player_id' => $this->integer(11)->defaultValue(0),
            'scout_ready' => $this->integer(11)->defaultValue(0),
            'scout_season_id' => $this->integer(3)->defaultValue(0),
            'scout_style' => $this->integer(1)->defaultValue(0),
            'scout_team_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('scout_ready', self::TABLE, 'scout_ready');
        $this->createIndex('scout_season_id', self::TABLE, 'scout_season_id');
        $this->createIndex('scout_team_id', self::TABLE, 'scout_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
