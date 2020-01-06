<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class RatingCountry
 * @package common\models\db
 *
 * @property int $rating_country_id
 * @property int $rating_country_auto_place
 * @property int $rating_country_country_id
 * @property int $rating_country_league_place
 * @property int $rating_country_stadium_place
 */
class RatingCountry extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_country}}';
    }
}
