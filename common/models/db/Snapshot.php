<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Snapshot
 * @package common\models\db
 *
 * @property int $snapshot_id
 * @property int $snapshot_base
 * @property float $snapshot_base_total
 * @property float $snapshot_base_medical
 * @property float $snapshot_base_physical
 * @property float $snapshot_base_school
 * @property float $snapshot_base_scout
 * @property float $snapshot_base_training
 * @property int $snapshot_country
 * @property int $snapshot_date
 * @property int $snapshot_manager
 * @property float $snapshot_manager_vip_percent
 * @property int $snapshot_manager_with_team
 * @property int $snapshot_player
 * @property float $snapshot_player_age
 * @property float $snapshot_player_c
 * @property float $snapshot_player_gk
 * @property float $snapshot_player_in_team
 * @property float $snapshot_player_ld
 * @property float $snapshot_player_lw
 * @property float $snapshot_player_rd
 * @property float $snapshot_player_rw
 * @property float $snapshot_player_power
 * @property float $snapshot_player_special_percent_no
 * @property float $snapshot_player_special_percent_one
 * @property float $snapshot_player_special_percent_two
 * @property float $snapshot_player_special_percent_three
 * @property float $snapshot_player_special_percent_four
 * @property float $snapshot_player_special_percent_athletic
 * @property float $snapshot_player_special_percent_combine
 * @property float $snapshot_player_special_percent_idol
 * @property float $snapshot_player_special_percent_leader
 * @property float $snapshot_player_special_percent_position
 * @property float $snapshot_player_special_percent_power
 * @property float $snapshot_player_special_percent_reaction
 * @property float $snapshot_player_special_percent_shot
 * @property float $snapshot_player_special_percent_speed
 * @property float $snapshot_player_special_percent_stick
 * @property float $snapshot_player_special_percent_tackle
 * @property float $snapshot_player_with_position_percent
 * @property int $snapshot_season_id
 * @property int $snapshot_team
 * @property int $snapshot_team_finance
 * @property float $snapshot_team_to_manager
 * @property int $snapshot_stadium
 */
class Snapshot extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%snapshot}}';
    }
}
