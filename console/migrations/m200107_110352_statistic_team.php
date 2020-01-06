<?php

use yii\db\Migration;

/**
 * Class m200107_110352_statistic_team
 */
class m200107_110352_statistic_team extends Migration
{
    const TABLE = '{{%statistic_team}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'statistic_team_id' => $this->primaryKey(11),
            'statistic_team_championship_playoff' => $this->integer(1)->defaultValue(0),
            'statistic_team_country_id' => $this->integer(3)->defaultValue(0),
            'statistic_team_division_id' => $this->integer(1)->defaultValue(0),
            'statistic_team_game' => $this->integer(2)->defaultValue(0),
            'statistic_team_game_no_pass' => $this->integer(2)->defaultValue(0),
            'statistic_team_game_no_score' => $this->integer(2)->defaultValue(0),
            'statistic_team_loose' => $this->integer(2)->defaultValue(0),
            'statistic_team_loose_over' => $this->integer(2)->defaultValue(0),
            'statistic_team_loose_shootout' => $this->integer(2)->defaultValue(0),
            'statistic_team_national_id' => $this->integer(5)->defaultValue(0),
            'statistic_team_pass' => $this->integer(2)->defaultValue(0),
            'statistic_team_penalty_minute' => $this->integer(2)->defaultValue(0),
            'statistic_team_penalty_minute_opponent' => $this->integer(2)->defaultValue(0),
            'statistic_team_score' => $this->integer(2)->defaultValue(0),
            'statistic_team_season_id' => $this->integer(3)->defaultValue(0),
            'statistic_team_team_id' => $this->integer(11)->defaultValue(0),
            'statistic_team_tournament_type_id' => $this->integer(1)->defaultValue(0),
            'statistic_team_win' => $this->integer(2)->defaultValue(0),
            'statistic_team_win_over' => $this->integer(2)->defaultValue(0),
            'statistic_team_win_percent' => $this->decimal(5, 2)->defaultValue(0),
            'statistic_team_win_shootout' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('statistic_team_country_id', self::TABLE, 'statistic_team_country_id');
        $this->createIndex('statistic_team_division_id', self::TABLE, 'statistic_team_division_id');
        $this->createIndex('statistic_team_national_id', self::TABLE, 'statistic_team_national_id');
        $this->createIndex('statistic_team_season_id', self::TABLE, 'statistic_team_season_id');
        $this->createIndex('statistic_team_team_id', self::TABLE, 'statistic_team_team_id');
        $this->createIndex('statistic_team_tournament_type_id', self::TABLE, 'statistic_team_tournament_type_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
