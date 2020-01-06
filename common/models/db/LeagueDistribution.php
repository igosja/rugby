<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LeagueDistribution
 * @package common\models\db
 *
 * @property int $league_distribution_id
 * @property int $league_distribution_country_id
 * @property int $league_distribution_group
 * @property int $league_distribution_qualification_3
 * @property int $league_distribution_qualification_2
 * @property int $league_distribution_qualification_1
 * @property int $league_distribution_season_id
 */
class LeagueDistribution extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%league_distribution}}';
    }
}
