<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000114PhysicalChange
 * @package console\migrations
 */
class M200101000114PhysicalChange extends Migration
{
    private const TABLE = '{{%physical_change}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
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

        $this->addForeignKey('physical_change_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('physical_change_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('physical_change_schedule_id', self::TABLE, 'schedule_id', '{{%schedule}}', 'id');
        $this->addForeignKey('physical_change_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('player_team_schedule', self::TABLE, ['player_id', 'team_id', 'schedule_id'], true);

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
