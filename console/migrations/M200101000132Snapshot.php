<?php

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
                'manager_vip_percent' => $this->decimal(5, 2)->defaultValue(0),
                'manager_with_team' => $this->integer(11)->defaultValue(0),
                'player' => $this->integer(11)->defaultValue(0),
                'player_age' => $this->decimal(4, 2)->defaultValue(0),
                'player_c' => $this->decimal(5, 2)->defaultValue(0),
                'player_gk' => $this->decimal(5, 2)->defaultValue(0),
                'player_in_team' => $this->decimal(5, 2)->defaultValue(0),
                'player_ld' => $this->decimal(5, 2)->defaultValue(0),
                'player_lw' => $this->decimal(5, 2)->defaultValue(0),
                'player_rd' => $this->decimal(5, 2)->defaultValue(0),
                'player_rw' => $this->decimal(5, 2)->defaultValue(0),
                'player_power' => $this->decimal(6, 2)->defaultValue(0),
                'player_special_percent_no' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_one' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_two' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_three' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_four' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_athletic' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_combine' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_idol' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_leader' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_position' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_power' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_reaction' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_shot' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_speed' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_stick' => $this->decimal(5, 2)->defaultValue(0),
                'player_special_percent_tackle' => $this->decimal(5, 2)->defaultValue(0),
                'player_with_position_percent' => $this->decimal(5, 2)->defaultValue(0),
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
