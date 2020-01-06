<?php

use yii\db\Migration;

/**
 * Class m200106_154810_achievement_player
 */
class m200106_154810_achievement_player extends Migration
{
    const TABLE = '{{%achievement_player}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'achievement_player_id' => $this->primaryKey(11),
            'achievement_player_country_id' => $this->integer(3)->defaultValue(0),
            'achievement_player_division_id' => $this->integer(5)->defaultValue(0),
            'achievement_player_is_playoff' => $this->integer(1)->defaultValue(0),
            'achievement_player_national_id' => $this->integer(5)->defaultValue(0),
            'achievement_player_place' => $this->integer(2)->defaultValue(0),
            'achievement_player_player_id' => $this->integer(11)->defaultValue(0),
            'achievement_player_season_id' => $this->integer(3)->defaultValue(0),
            'achievement_player_stage_id' => $this->integer(2)->defaultValue(0),
            'achievement_player_team_id' => $this->integer(5)->defaultValue(0),
            'achievement_player_tournament_type_id' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex('achievement_player_player_id', self::TABLE, 'achievement_player_player_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
