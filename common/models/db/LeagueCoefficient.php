<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LeagueCoefficient
 * @package common\models\db
 *
 * @property int $league_coefficient_id
 * @property int $league_coefficient_country_id
 * @property int $league_coefficient_loose
 * @property int $league_coefficient_point
 * @property int $league_coefficient_season_id
 * @property int $league_coefficient_team_id
 * @property int $league_coefficient_win
 */
class LeagueCoefficient extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%league_coefficient}}';
    }
}
