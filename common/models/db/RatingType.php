<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class RatingType
 * @package common\models\db
 *
 * @property int $rating_type_id
 * @property string $rating_type_name
 * @property int $rating_type_order
 * @property int $rating_type_rating_chapter_id
 */
class RatingType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_type}}';
    }
}
