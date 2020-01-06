<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class StatisticChapter
 * @package common\models\db
 *
 * @property int $statistic_chapter_id
 * @property string $statistic_chapter_name
 * @property int $statistic_chapter_order
 */
class StatisticChapter extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_chapter}}';
    }
}
