<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m200107_093746_conference
 * @package console\migrations
 */
class m200107_093746_conference extends Migration
{
    private const TABLE = '{{%conference}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'conference_id' => $this->primaryKey(11),
            'conference_bonus_loose' => $this->integer(2)->defaultValue(0),
            'conference_bonus_tries' => $this->integer(2)->defaultValue(0),
            'conference_difference' => $this->integer(3)->defaultValue(0),
            'conference_draw' => $this->integer(2)->defaultValue(0),
            'conference_game' => $this->integer(2)->defaultValue(0),
            'conference_loose' => $this->integer(2)->defaultValue(0),
            'conference_place' => $this->integer(2)->defaultValue(0),
            'conference_point' => $this->integer(2)->defaultValue(0),
            'conference_point_against' => $this->integer(4)->defaultValue(0),
            'conference_point_for' => $this->integer(4)->defaultValue(0),
            'conference_season_id' => $this->integer(3)->defaultValue(0),
            'conference_team_id' => $this->integer(11)->defaultValue(0),
            'conference_tries_against' => $this->integer(3)->defaultValue(0),
            'conference_tries_for' => $this->integer(3)->defaultValue(0),
            'conference_win' => $this->integer(2)->defaultValue(0),
        ]);

        $this->createIndex('conference_season_id', self::TABLE, 'conference_season_id');
        $this->createIndex('conference_team_id', self::TABLE, 'conference_team_id');

        $this->batchInsert(self::TABLE, ['conference_place', 'conference_season_id', 'conference_team_id'], [
            [1, 1, 33],
            [2, 1, 34],
            [3, 1, 67],
            [4, 1, 68],
            [5, 1, 101],
            [6, 1, 102],
            [7, 1, 135],
            [8, 1, 136],
            [9, 1, 169],
            [10, 1, 170],
            [11, 1, 203],
            [12, 1, 204],
            [13, 1, 237],
            [14, 1, 238],
            [15, 1, 271],
            [16, 1, 272],
            [17, 1, 305],
            [18, 1, 306],
            [19, 1, 339],
            [20, 1, 340],

        ]);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
