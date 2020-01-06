<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Recommendation
 * @package common\models\db
 *
 * @property int $recommendation_id
 * @property int $recommendation_team_id
 * @property int $recommendation_user_id
 */
class Recommendation extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%recommendation}}';
    }
}
