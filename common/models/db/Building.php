<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Building
 * @package common\models\db
 *
 * @property int $building_id
 * @property string $building_name
 */
class Building extends AbstractActiveRecord
{
    public const BASE = 1;
    public const MEDICAL = 2;
    public const PHYSICAL = 3;
    public const SCHOOL = 4;
    public const SCOUT = 5;
    public const TRAINING = 6;

    public const MAX_LEVEL = 10;
    public const MIN_LEVEL = 0;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%building}}';
    }
}
