<?php

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
 * @property int $country
 * @property int $date
 * @property int $manager
 * @property float $manager_vip_percent
 * @property int $manager_with_team
 * @property int $player
 * @property float $player_age
 * @property float $player_c
 * @property float $player_gk
 * @property float $player_in_team
 * @property float $player_ld
 * @property float $player_lw
 * @property float $player_rd
 * @property float $player_rw
 * @property float $player_power
 * @property float $player_special_percent_no
 * @property float $player_special_percent_one
 * @property float $player_special_percent_two
 * @property float $player_special_percent_three
 * @property float $player_special_percent_four
 * @property float $player_special_percent_athletic
 * @property float $player_special_percent_combine
 * @property float $player_special_percent_idol
 * @property float $player_special_percent_leader
 * @property float $player_special_percent_position
 * @property float $player_special_percent_power
 * @property float $player_special_percent_reaction
 * @property float $player_special_percent_shot
 * @property float $player_special_percent_speed
 * @property float $player_special_percent_stick
 * @property float $player_special_percent_tackle
 * @property float $player_with_position_percent
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
                    'country',
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
