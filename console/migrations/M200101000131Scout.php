<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000131Scout
 * @package console\migrations
 */
class M200101000131Scout extends Migration
{
    private const TABLE = '{{%scout}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'is_school' => $this->boolean()->defaultValue(false),
                'is_style' => $this->boolean()->defaultValue(false),
                'percent' => $this->integer(3)->defaultValue(0),
                'player_id' => $this->integer(11)->notNull(),
                'ready' => $this->integer(11),
                'season_id' => $this->integer(3)->notNull(),
                'team_id' => $this->integer(11)->notNull(),
            ]
        );

        $this->addForeignKey('scout_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('scout_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('scout_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');

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
