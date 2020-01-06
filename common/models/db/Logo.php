<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Logo
 * @package common\models\db
 *
 * @property int $logo_id
 * @property int $logo_date
 * @property int $logo_team_id
 * @property string $logo_text
 * @property int $logo_user_id
 */
class Logo extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%logo}}';
    }
}
