<?php

use yii\db\Migration;

/**
 * Class m200107_112517_world_cup
 */
class m200113_112517_world_cup extends Migration
{
    const TABLE = '{{%world_cup}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'world_cup_id' => $this->primaryKey(11),
            'world_cup_bonus_loose' => $this->integer(2)->defaultValue(0),
            'world_cup_bonus_tries' => $this->integer(2)->defaultValue(0),
            'world_cup_difference' => $this->integer(3)->defaultValue(0),
            'world_cup_division_id' => $this->integer(1)->defaultValue(0),
            'world_cup_draw' => $this->integer(2)->defaultValue(0),
            'world_cup_game' => $this->integer(2)->defaultValue(0),
            'world_cup_loose' => $this->integer(2)->defaultValue(0),
            'world_cup_national_id' => $this->integer(3)->defaultValue(0),
            'world_cup_national_type_id' => $this->integer(1)->defaultValue(0),
            'world_cup_place' => $this->integer(2)->defaultValue(0),
            'world_cup_point' => $this->integer(2)->defaultValue(0),
            'world_cup_point_against' => $this->integer(4)->defaultValue(0),
            'world_cup_point_for' => $this->integer(4)->defaultValue(0),
            'world_cup_season_id' => $this->integer(3)->defaultValue(0),
            'world_cup_team_id' => $this->integer(11)->defaultValue(0),
            'world_cup_tries_against' => $this->integer(3)->defaultValue(0),
            'world_cup_tries_for' => $this->integer(3)->defaultValue(0),
            'world_cup_win' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('world_cup_division_id', self::TABLE, 'world_cup_division_id');
        $this->createIndex('world_cup_national_id', self::TABLE, 'world_cup_national_id');
        $this->createIndex('world_cup_national_type_id', self::TABLE, 'world_cup_national_type_id');
        $this->createIndex('world_cup_season_id', self::TABLE, 'world_cup_season_id');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
