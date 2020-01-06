<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class StatisticType
 * @package common\models\db
 *
 * @property int $statistic_type_id
 * @property string $statistic_type_name
 * @property int $statistic_type_order
 * @property string $statistic_type_select
 * @property int $statistic_type_statistic_chapter_id
 */
class StatisticType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%statistic_type}}';
    }
}
