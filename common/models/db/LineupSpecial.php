<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LineupSpecial
 * @package common\models\db
 *
 * @property int $lineup_special_id
 * @property int $lineup_special_level
 * @property int $lineup_special_lineup_id
 * @property int $lineup_special_special_id
 */
class LineupSpecial extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%lineup_special}}';
    }
}
