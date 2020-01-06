<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class RatingTeam
 * @package common\models\db
 *
 * @property int $rating_team_id
 * @property int $rating_team_age_place
 * @property int $rating_team_age_place_country
 * @property int $rating_team_base_place
 * @property int $rating_team_base_place_country
 * @property int $rating_team_finance_place
 * @property int $rating_team_finance_place_country
 * @property int $rating_team_player_place
 * @property int $rating_team_player_place_country
 * @property int $rating_team_power_vs_place
 * @property int $rating_team_power_vs_place_country
 * @property int $rating_team_price_base_place
 * @property int $rating_team_price_base_place_country
 * @property int $rating_team_price_stadium_place
 * @property int $rating_team_price_stadium_place_country
 * @property int $rating_team_price_total_place
 * @property int $rating_team_price_total_place_country
 * @property int $rating_team_salary_place
 * @property int $rating_team_salary_place_country
 * @property int $rating_team_stadium_place
 * @property int $rating_team_stadium_place_country
 * @property int $rating_team_team_id
 * @property int $rating_team_visitor_place
 * @property int $rating_team_visitor_place_country
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
}
