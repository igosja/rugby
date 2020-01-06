<?php

use yii\db\Migration;

/**
 * Class m200106_154516_achievement
 */
class m200106_154516_achievement extends Migration
{
    const TABLE = '{{%achievement}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'achievement_id' => $this->primaryKey(11),
            'achievement_country_id' => $this->integer(3)->defaultValue(0),
            'achievement_division_id' => $this->integer(5)->defaultValue(0),
            'achievement_is_playoff' => $this->integer(1)->defaultValue(0),
            'achievement_national_id' => $this->integer(5)->defaultValue(0),
            'achievement_place' => $this->integer(2)->defaultValue(0),
            'achievement_season_id' => $this->integer(3)->defaultValue(0),
            'achievement_stage_id' => $this->integer(2)->defaultValue(0),
            'achievement_team_id' => $this->integer(5)->defaultValue(0),
            'achievement_tournament_type_id' => $this->integer(1)->defaultValue(0),
            'achievement_user_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('achievement_national_id', self::TABLE, 'achievement_national_id');
        $this->createIndex('achievement_team_id', self::TABLE, 'achievement_team_id');
        $this->createIndex('achievement_user_id', self::TABLE, 'achievement_user_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
