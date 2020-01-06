<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class RatingUser
 * @package common\models\db
 *
 * @property int $rating_user_id
 * @property int $rating_user_rating_place
 * @property int $rating_user_user_id
 */
class RatingUser extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_user}}';
    }
}
