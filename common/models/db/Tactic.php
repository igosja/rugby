<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Tactic
 * @package common\models\db
 *
 * @property int $tactic_id
 * @property string $tactic_name
 */
class Tactic extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%tactic}}';
    }
}
