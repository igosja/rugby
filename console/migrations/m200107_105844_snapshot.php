<?php

use yii\db\Migration;

/**
 * Class m200107_105844_snapshot
 */
class m200107_105844_snapshot extends Migration
{
    const TABLE = '{{%snapshot}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'snapshot_id' => $this->primaryKey(11),
            'snapshot_base' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_base_total' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_base_medical' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_base_physical' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_base_school' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_base_scout' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_base_training' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_country' => $this->integer(3)->defaultValue(0),
            'snapshot_date' => $this->integer(11)->defaultValue(0),
            'snapshot_manager' => $this->integer(11)->defaultValue(0),
            'snapshot_manager_vip_percent' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_manager_with_team' => $this->integer(11)->defaultValue(0),
            'snapshot_player' => $this->integer(11)->defaultValue(0),
            'snapshot_player_age' => $this->decimal(4, 2)->defaultValue(0),
            'snapshot_player_c' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_gk' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_in_team' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_ld' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_lw' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_rd' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_rw' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_power' => $this->decimal(6, 2)->defaultValue(0),
            'snapshot_player_special_percent_no' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_one' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_two' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_three' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_four' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_athletic' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_combine' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_idol' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_leader' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_position' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_power' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_reaction' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_shot' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_speed' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_stick' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_special_percent_tackle' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_player_with_position_percent' => $this->decimal(5, 2)->defaultValue(0),
            'snapshot_season_id' => $this->integer(3)->defaultValue(0),
            'snapshot_team' => $this->integer(11)->defaultValue(0),
            'snapshot_team_finance' => $this->integer(11)->defaultValue(0),
            'snapshot_team_to_manager' => $this->decimal(3, 2)->defaultValue(0),
            'snapshot_stadium' => $this->integer(5)->defaultValue(0),
        ]);

        $this->createIndex('snapshot_date', self::TABLE, 'snapshot_date');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
