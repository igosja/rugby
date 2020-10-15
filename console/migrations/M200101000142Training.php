<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000142Training
 * @package console\migrations
 */
class M200101000142Training extends Migration
{
    private const TABLE = '{{%training}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'percent' => $this->integer(3)->defaultValue(0),
                'player_id' => $this->integer(11)->notNull(),
                'position_id' => $this->integer(1),
                'is_power' => $this->boolean()->defaultValue(false),
                'ready' => $this->integer(11)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
                'special_id' => $this->integer(2),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('training_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('training_position_id', self::TABLE, 'position_id', '{{%position}}', 'id');
        $this->addForeignKey('training_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('training_special_id', self::TABLE, 'special_id', '{{%special}}', 'id');
        $this->addForeignKey('training_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('ready', self::TABLE, 'ready');

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
