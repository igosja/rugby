<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_110326_statistic_player
 * @package console\migrations
 */
class m200107_110326_statistic_player extends Migration
{
    private const TABLE = '{{%statistic_player}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'statistic_player_id' => $this->primaryKey(11),
            'statistic_player_assist' => $this->integer(3)->defaultValue(0),
            'statistic_player_assist_power' => $this->integer(3)->defaultValue(0),
            'statistic_player_assist_short' => $this->integer(3)->defaultValue(0),
            'statistic_player_shootout_win' => $this->integer(2)->defaultValue(0),
            'statistic_player_championship_playoff' => $this->integer(1)->defaultValue(0),
            'statistic_player_country_id' => $this->integer(3)->defaultValue(0),
            'statistic_player_division_id' => $this->integer(1)->defaultValue(0),
            'statistic_player_face_off' => $this->integer(3)->defaultValue(0),
            'statistic_player_face_off_percent' => $this->decimal(5, 2)->defaultValue(0),
            'statistic_player_face_off_win' => $this->integer(3)->defaultValue(0),
            'statistic_player_game' => $this->integer(2)->defaultValue(0),
            'statistic_player_game_with_shootout' => $this->integer(2)->defaultValue(0),
            'statistic_player_is_gk' => $this->integer(1)->defaultValue(0),
            'statistic_player_loose' => $this->integer(2)->defaultValue(0),
            'statistic_player_national_id' => $this->integer(5)->defaultValue(0),
            'statistic_player_pass' => $this->integer(3)->defaultValue(0),
            'statistic_player_pass_per_game' => $this->decimal(4, 2)->defaultValue(0),
            'statistic_player_penalty' => $this->integer(3)->defaultValue(0),
            'statistic_player_player_id' => $this->integer(11)->defaultValue(0),
            'statistic_player_plus_minus' => $this->integer(3)->defaultValue(0),
            'statistic_player_point' => $this->integer(3)->defaultValue(0),
            'statistic_player_save' => $this->integer(3)->defaultValue(0),
            'statistic_player_save_percent' => $this->decimal(5, 2)->defaultValue(0),
            'statistic_player_score' => $this->integer(3)->defaultValue(0),
            'statistic_player_score_draw' => $this->integer(3)->defaultValue(0),
            'statistic_player_score_power' => $this->integer(3)->defaultValue(0),
            'statistic_player_score_short' => $this->integer(3)->defaultValue(0),
            'statistic_player_score_shot_percent' => $this->decimal(5, 2)->defaultValue(0),
            'statistic_player_score_win' => $this->integer(2)->defaultValue(0),
            'statistic_player_season_id' => $this->integer(3)->defaultValue(0),
            'statistic_player_shot' => $this->integer(3)->defaultValue(0),
            'statistic_player_shot_gk' => $this->integer(3)->defaultValue(0),
            'statistic_player_shot_per_game' => $this->decimal(4, 2)->defaultValue(0),
            'statistic_player_shutout' => $this->integer(3)->defaultValue(0),
            'statistic_player_team_id' => $this->integer(11)->defaultValue(0),
            'statistic_player_tournament_type_id' => $this->integer(1)->defaultValue(0),
            'statistic_player_win' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('statistic_player_country_id', self::TABLE, 'statistic_player_country_id');
        $this->createIndex('statistic_player_division_id', self::TABLE, 'statistic_player_division_id');
        $this->createIndex('statistic_player_national_id', self::TABLE, 'statistic_player_national_id');
        $this->createIndex('statistic_player_player_id', self::TABLE, 'statistic_player_player_id');
        $this->createIndex('statistic_player_season_id', self::TABLE, 'statistic_player_season_id');
        $this->createIndex('statistic_player_team_id', self::TABLE, 'statistic_player_team_id');
        $this->createIndex('statistic_player_tournament_type_id', self::TABLE, 'statistic_player_tournament_type_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
