<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000096History
 * @package console\migrations
 */
class M200101000096History extends Migration
{
    private const TABLE = '{{%history}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'building_id' => $this->integer(1),
                'federation_id' => $this->integer(3),
                'date' => $this->integer(11)->notNull(),
                'fire_reason_id' => $this->integer(2),
                'game_id' => $this->integer(11),
                'history_text_id' => $this->integer(2)->notNull(),
                'national_id' => $this->integer(3),
                'player_id' => $this->integer(11),
                'position_id' => $this->integer(2),
                'season_id' => $this->integer(3)->notNull(),
                'second_team_id' => $this->integer(11),
                'second_user_id' => $this->integer(11),
                'special_id' => $this->integer(2),
                'team_id' => $this->integer(11),
                'user_id' => $this->integer(11),
                'value' => $this->integer(11),
            ]
        );

        $this->addForeignKey('history_building_id', self::TABLE, 'building_id', '{{%building}}', 'id');
        $this->addForeignKey('history_federation_id', self::TABLE, 'federation_id', '{{%federation}}', 'id');
        $this->addForeignKey('history_fire_reason_id', self::TABLE, 'fire_reason_id', '{{%fire_reason}}', 'id');
        $this->addForeignKey('history_game_id', self::TABLE, 'game_id', '{{%game}}', 'id');
        $this->addForeignKey('history_history_text_id', self::TABLE, 'history_text_id', '{{%history_text}}', 'id');
        $this->addForeignKey('history_national_id', self::TABLE, 'national_id', '{{%national}}', 'id');
        $this->addForeignKey('history_player_id', self::TABLE, 'player_id', '{{%player}}', 'id');
        $this->addForeignKey('history_position_id', self::TABLE, 'position_id', '{{%position}}', 'id');
        $this->addForeignKey('history_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');
        $this->addForeignKey('history_second_team_id', self::TABLE, 'second_team_id', '{{%team}}', 'id');
        $this->addForeignKey('history_second_user_id', self::TABLE, 'second_user_id', '{{%user}}', 'id');
        $this->addForeignKey('history_special_id', self::TABLE, 'special_id', '{{%special}}', 'id');
        $this->addForeignKey('history_team_id', self::TABLE, 'team_id', '{{%team}}', 'id');
        $this->addForeignKey('history_user_id', self::TABLE, 'user_id', '{{%user}}', 'id');

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
