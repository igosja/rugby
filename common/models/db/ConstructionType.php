<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ConstructionType
 * @package common\models\db
 *
 * @property int $construction_type_id
 * @property string $construction_type_name
 */
class ConstructionType extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%construction_type}}';
    }
}
