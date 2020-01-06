<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Rudeness
 * @package common\models\db
 *
 * @property int $rudeness_id
 * @property string $rudeness_name
 */
class Rudeness extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%rudeness}}';
    }
}
