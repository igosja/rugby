<?php

use yii\db\Migration;

/**
 * Class m200107_103627_national_player_day
 */
class m200107_103627_national_player_day extends Migration
{
    const TABLE = '{{%national_player_day}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'national_player_day_id' => $this->primaryKey(11),
            'national_player_day_day' => $this->integer(3)->defaultValue(0),
            'national_player_day_national_id' => $this->integer(3)->defaultValue(0),
            'national_player_day_player_id' => $this->integer(11)->defaultValue(0),
            'national_player_day_team_id' => $this->integer(11)->defaultValue(0),
        ]);

        $this->createIndex('national_player_day_national_id', self::TABLE, 'national_player_day_national_id');
        $this->createIndex('national_player_day_player_id', self::TABLE, 'national_player_day_player_id');
        $this->createIndex('national_player_day_team_id', self::TABLE, 'national_player_day_team_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
