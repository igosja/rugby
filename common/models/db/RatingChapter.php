<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class RatingChapter
 * @package common\models\db
 *
 * @property int $rating_chapter_id
 * @property string $rating_chapter_name
 * @property int $rating_chapter_order
 */
class RatingChapter extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rating_chapter}}';
    }
}
