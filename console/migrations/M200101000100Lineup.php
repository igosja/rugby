<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000100Lineup
 * @package console\migrations
 */
class M200101000100Lineup extends Migration
{
    private const TABLE = '{{%lineup}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'age' => $this->integer(2)->defaultValue(0),
                'conversion' => $this->integer(2)->defaultValue(0),
                'drop_goal' => $this->integer(1)->defaultValue(0),
                'game_id' => $this->integer(11)->notNull(),
                'is_captain' => $this->boolean()->defaultValue(false),
                'minute' => $this->integer(2)->defaultValue(0),
                'national_id' => $this->integer(3),
                'player_id' => $this->integer(11)->notNull(),
                'point' => $this->integer(2)->defaultValue(0),
                'position_id' => $this->integer(2)->notNull(),
                'power_change' => $this->integer(1)->defaultValue(0),
                'power_nominal' => $this->integer(3)->defaultValue(0),
                'power_real' => $this->integer(3)->defaultValue(0),
                'red_card' => $this->integer(1)->defaultValue(0),
                'team_id' => $this->integer(11),
                'try' => $this->integer(1)->defaultValue(0),
                'yellow_card' => $this->integer(1)->defaultValue(0),
            ]
        );

        $this->addForeignKey('lineup_game_id', self::TABLE, 'game_id', '{{%game}}', 'id');
        $this->addForeignKey('lineup_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('lineup_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('lineup_position_id', self::TABLE, 'position_id', '{{%position}}', 'id');
        $this->addForeignKey('lineup_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

        $this->createIndex('game_player', self::TABLE, ['game_id', 'player_id'], true);

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
