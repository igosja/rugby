<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class RatingTeam
 * @package common\models\db
 *
 * @property int $id
 * @property int $age_place
 * @property int $age_place_country
 * @property int $base_place
 * @property int $base_place_country
 * @property int $finance_place
 * @property int $finance_place_country
 * @property int $player_place
 * @property int $player_place_country
 * @property int $power_vs_place
 * @property int $power_vs_place_country
 * @property int $price_base_place
 * @property int $price_base_place_country
 * @property int $price_stadium_place
 * @property int $price_stadium_place_country
 * @property int $price_total_place
 * @property int $price_total_place_country
 * @property int $salary_place
 * @property int $salary_place_country
 * @property int $stadium_place
 * @property int $stadium_place_country
 * @property int $team_id
 * @property int $visitor_place
 * @property int $visitor_place_country
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
                    'age_place_country',
                    'base_place',
                    'base_place_country',
                    'finance_place',
                    'finance_place_country',
                    'player_place',
                    'player_place_country',
                    'power_vs_place',
                    'power_vs_place_country',
                    'price_base_place',
                    'price_base_place_country',
                    'price_stadium_place',
                    'price_stadium_place_country',
                    'price_total_place',
                    'price_total_place_country',
                    'salary_place',
                    'salary_place_country',
                    'stadium_place',
                    'stadium_place_country',
                    'team_id',
                    'visitor_place',
                    'visitor_place_country',
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
