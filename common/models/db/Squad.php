<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Squad
 * @package common\models\db
 *
 * @property int $squad_id
 * @property string $squad_color
 * @property string $squad_name
 */
class Squad extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%squad}}';
    }
}
