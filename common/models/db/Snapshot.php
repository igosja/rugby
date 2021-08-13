<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Snapshot
 * @package common\models\db
 *
 * @property int $id
 * @property int $base
 * @property float $base_total
 * @property float $base_medical
 * @property float $base_physical
 * @property float $base_school
 * @property float $base_scout
 * @property float $base_training
 * @property int $federation
 * @property int $date
 * @property int $manager
 * @property float $manager_vip
 * @property int $manager_with_team
 * @property int $player
 * @property float $player_age
 * @property float $player_in_team
 * @property float $player_position_centre
 * @property float $player_position_eight
 * @property float $player_position_flanker
 * @property float $player_position_fly_half
 * @property float $player_position_full_back
 * @property float $player_position_hooker
 * @property float $player_position_lock
 * @property float $player_position_prop
 * @property float $player_position_scrum_half
 * @property float $player_position_wing
 * @property float $player_power
 * @property float $player_special_no
 * @property float $player_special_one
 * @property float $player_special_two
 * @property float $player_special_three
 * @property float $player_special_four
 * @property float $player_special_athletic
 * @property float $player_special_combine
 * @property float $player_special_idol
 * @property float $player_special_leader
 * @property float $player_special_moul
 * @property float $player_special_pass
 * @property float $player_special_power
 * @property float $player_special_ruck
 * @property float $player_special_scrum
 * @property float $player_special_speed
 * @property float $player_special_tackle
 * @property float $player_with_position
 * @property int $season_id
 * @property int $team
 * @property int $team_finance
 * @property float $team_to_manager
 * @property int $stadium
 *
 * @property-read Season $season
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

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                [
                    'base',
                    'base_total',
                    'base_medical',
                    'base_physical',
                    'base_school',
                    'base_scout',
                    'base_training',
                    'federation',
                    'date',
                    'manager',
                    'manager_vip_percent',
                    'manager_with_team',
                    'player',
                    'player_age',
                    'player_c',
                    'player_gk',
                    'player_in_team',
                    'player_ld',
                    'player_lw',
                    'player_rd',
                    'player_rw',
                    'player_power',
                    'player_special_percent_no',
                    'player_special_percent_one',
                    'player_special_percent_two',
                    'player_special_percent_three',
                    'player_special_percent_four',
                    'player_special_percent_athletic',
                    'player_special_percent_combine',
                    'player_special_percent_idol',
                    'player_special_percent_leader',
                    'player_special_percent_position',
                    'player_special_percent_power',
                    'player_special_percent_reaction',
                    'player_special_percent_shot',
                    'player_special_percent_speed',
                    'player_special_percent_stick',
                    'player_special_percent_tackle',
                    'player_with_position_percent',
                    'season_id',
                    'team',
                    'team_finance',
                    'team_to_manager',
                    'stadium',
                ],
                'required'
            ],
            [['season_id'], 'exist', 'targetRelation' => 'season'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSeason(): ActiveQuery
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }
}
