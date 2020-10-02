<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000069Conference
 * @package console\migrations
 */
class M200101000069Conference extends Migration
{
    private const TABLE = '{{%conference}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'bonus_loose' => $this->integer(2)->defaultValue(0),
                'bonus_tries' => $this->integer(2)->defaultValue(0),
                'difference' => $this->integer(3)->defaultValue(0),
                'draw' => $this->integer(2)->defaultValue(0),
                'game' => $this->integer(2)->defaultValue(0),
                'loose' => $this->integer(2)->defaultValue(0),
                'place' => $this->integer(2)->notNull(),
                'point' => $this->integer(2)->defaultValue(0),
                'point_against' => $this->integer(4)->defaultValue(0),
                'point_for' => $this->integer(4)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
                'tries_against' => $this->integer(3)->defaultValue(0),
                'tries_for' => $this->integer(3)->defaultValue(0),
                'win' => $this->integer(2)->defaultValue(0),
            ]
        );

        $this->addForeignKey('conference_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('conference_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('team_season', self::TABLE, ['team_id', 'season_id'], true);

        $this->batchInsert(
            self::TABLE,
            ['place', 'season_id', 'team_id'],
            [
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
            ]
        );

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown(): bool
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
