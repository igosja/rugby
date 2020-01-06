<?php

use yii\db\Migration;

/**
 * Class m200107_093746_conference
 */
class m200107_093746_conference extends Migration
{
    const TABLE = '{{%conference}}';

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

        $this->batchInsert(self::TABLE, ['conference_season_id', 'conference_team_id'], [
            [1, 33],
            [1, 34],
            [1, 67],
            [1, 68],
            [1, 101],
            [1, 102],
            [1, 135],
            [1, 136],
            [1, 169],
            [1, 170],
            [1, 203],
            [1, 204],
            [1, 237],
            [1, 238],
            [1, 271],
            [1, 272],
            [1, 305],
            [1, 306],
            [1, 339],
            [1, 340],

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
