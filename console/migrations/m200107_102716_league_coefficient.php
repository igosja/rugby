<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_102716_league_coefficient
 * @package console\migrations
 */
class m200107_102716_league_coefficient extends Migration
{
    private const TABLE = '{{%league_coefficient}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'league_coefficient_id' => $this->primaryKey(11),
            'league_coefficient_country_id' => $this->integer(3)->defaultValue(0),
            'league_coefficient_loose' => $this->integer(2)->defaultValue(0),
            'league_coefficient_point' => $this->integer(2)->defaultValue(0),
            'league_coefficient_season_id' => $this->integer(3)->defaultValue(0),
            'league_coefficient_team_id' => $this->integer(11)->defaultValue(0),
            'league_coefficient_win' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('league_coefficient_country_id', self::TABLE, 'league_coefficient_country_id');
        $this->createIndex('league_coefficient_season_id', self::TABLE, 'league_coefficient_season_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
