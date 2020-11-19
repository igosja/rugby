<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000098LeagueCoefficient
 * @package console\migrations
 */
class M200101000098LeagueCoefficient extends Migration
{
    private const TABLE = '{{%league_coefficient}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'draw' => $this->integer(2)->defaultValue(0),
                'federation_id' => $this->integer(3)->notNull(),
                'loose' => $this->integer(2)->defaultValue(0),
                'point' => $this->integer(2)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
                'win' => $this->integer(2)->defaultValue(0),
            ]
        );

        $this->addForeignKey('league_coefficient_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('league_coefficient_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('league_coefficient_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
