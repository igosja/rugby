<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Swiss
 * @package common\models\db
 *
 * @property int $swiss_id
 * @property int $swiss_guest
 * @property int $swiss_home
 * @property int $swiss_place
 * @property int $swiss_team_id
 */
class Swiss extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%swiss}}';
    }
}
