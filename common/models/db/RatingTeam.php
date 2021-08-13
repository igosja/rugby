<?php

// TODO refactor

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class RatingTeam
 * @package common\models\db
 *
 * @property int $id
 * @property int $age_place
 * @property int $age_place_federation
 * @property int $base_place
 * @property int $base_place_federation
 * @property int $finance_place
 * @property int $finance_place_federation
 * @property int $player_place
 * @property int $player_place_federation
 * @property int $power_vs_place
 * @property int $power_vs_place_federation
 * @property int $price_base_place
 * @property int $price_base_place_federation
 * @property int $price_stadium_place
 * @property int $price_stadium_place_federation
 * @property int $price_total_place
 * @property int $price_total_place_federation
 * @property int $salary_place
 * @property int $salary_place_federation
 * @property int $stadium_place
 * @property int $stadium_place_federation
 * @property int $team_id
 * @property int $visitor_place
 * @property int $visitor_place_federation
 *
 * @property-read Team $team
 */
class RatingTeam extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_team}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['team_id'], 'required'],
            [
                [
                    'age_place',
                    'age_place_federation',
                    'base_place',
                    'base_place_federation',
                    'finance_place',
                    'finance_place_federation',
                    'player_place',
                    'player_place_federation',
                    'power_vs_place',
                    'power_vs_place_federation',
                    'price_base_place',
                    'price_base_place_federation',
                    'price_stadium_place',
                    'price_stadium_place_federation',
                    'price_total_place',
                    'price_total_place_federation',
                    'salary_place',
                    'salary_place_federation',
                    'stadium_place',
                    'stadium_place_federation',
                    'team_id',
                    'visitor_place',
                    'visitor_place_federation',
                ],
                'integer',
                'min' => 0
            ],
            [['team_id'], 'exist', 'targetRelation' => 'team'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }
}
