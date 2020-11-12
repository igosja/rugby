<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000097League
 * @package console\migrations
 */
class M200101000097League extends Migration
{
    private const TABLE = '{{%league}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'bonus_loose' => $this->integer(1)->defaultValue(0),
                'bonus_tries' => $this->integer(1)->defaultValue(0),
                'difference' => $this->integer(3)->defaultValue(0),
                'draw' => $this->integer(1)->defaultValue(0),
                'game' => $this->integer(1)->defaultValue(0),
                'group' => $this->integer(1)->defaultValue(0),
                'loose' => $this->integer(1)->defaultValue(0),
                'place' => $this->integer(1)->notNull(),
                'point' => $this->integer(2)->defaultValue(0),
                'point_against' => $this->integer(3)->defaultValue(0),
                'point_for' => $this->integer(3)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
                'tries_against' => $this->integer(2)->defaultValue(0),
                'tries_for' => $this->integer(2)->defaultValue(0),
                'win' => $this->integer(1)->defaultValue(0),
            ]
        );

        $this->addForeignKey('league_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('league_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('team_season', self::TABLE, ['team_id', 'season_id'], true);

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
