<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Language
 * @package common\models\db
 *
 * @property int $language_id
 * @property string $language_code
 * @property string $language_name
 */
class Language extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%language}}';
    }
}
