<?php

// TODO refactor

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200101000132Snapshot
 * @package console\migrations
 */
class M200101000132Snapshot extends Migration
{
    private const TABLE = '{{%snapshot}}';

    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(11),
                'base' => $this->decimal(4, 2)->defaultValue(0),
                'base_total' => $this->decimal(4, 2)->defaultValue(0),
                'base_medical' => $this->decimal(4, 2)->defaultValue(0),
                'base_physical' => $this->decimal(4, 2)->defaultValue(0),
                'base_school' => $this->decimal(4, 2)->defaultValue(0),
                'base_scout' => $this->decimal(4, 2)->defaultValue(0),
                'base_training' => $this->decimal(4, 2)->defaultValue(0),
                'federation' => $this->integer(3)->defaultValue(0),
                'date' => $this->integer(11)->notNull(),
                'manager' => $this->integer(11)->defaultValue(0),
                'manager_vip' => $this->decimal(5, 2)->defaultValue(0),
                'manager_with_team' => $this->integer(11)->defaultValue(0),
                'player' => $this->integer(11)->defaultValue(0),
                'player_age' => $this->decimal(4, 2)->defaultValue(0),
                'player_in_team' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_centre' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_eight' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_flanker' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_fly_half' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_full_back' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_hooker' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_lock' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_prop' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_scrum_half' => $this->decimal(5, 2)->defaultValue(0),
                'player_position_wing' => $this->decimal(5, 2)->defaultValue(0),
                'player_power' => $this->decimal(6, 2)->defaultValue(0),
                'player_special_no' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_one' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_two' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_three' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_four' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_athletic' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_combine' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_idol' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_leader' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_moul' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_pass' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_power' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_ruck' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_scrum' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_speed' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_tackle' => $this->decimal(5, 2)->defaultValue(0),
                'player_with_position' => $this->decimal(5, 2)->defaultValue(0),
                'season_id' => $this->integer(3)->notNull(),
                'team' => $this->integer(11)->defaultValue(0),
                'team_finance' => $this->integer(11)->defaultValue(0),
                'team_to_manager' => $this->decimal(3, 2)->defaultValue(0),
                'stadium' => $this->integer(5)->defaultValue(0),
            ]
        );

        $this->addForeignKey('snapshot_season_id', self::TABLE, 'season_id', '{{%season}}', 'id');

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
