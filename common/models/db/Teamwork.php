<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Teamwork
 * @package common\models\db
 *
 * @property int $teamwork_id
 * @property int $teamwork_player_id_1
 * @property int $teamwork_player_id_2
 * @property int $teamwork_value
 */
class Teamwork extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%teamwork}}';
    }
}
