<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000115Planning
 * @package console\migrations
 */
class M200101000115Planning extends Migration
{
    private const TABLE = '{{%planning}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'player_id' => $this->integer(11)->notNull(),
                'season_id' => $this->integer(3)->notNull(),
                'schedule_id' => $this->integer(11)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('planning_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('planning_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('planning_schedule_id', self::TABLE, 'schedule_id', '{{%schedule}}', 'id');
        $this->addForeignKey('planning_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('player_team_schedule', self::TABLE, ['player_id', 'team_id', 'schedule_id'], true);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
