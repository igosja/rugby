<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Support
 * @package common\models\db
 *
 * @property int $support_id
 * @property int $support_admin_id
 * @property int $support_country_id
 * @property int $support_date
 * @property int $support_inside
 * @property int $support_president_id
 * @property int $support_question
 * @property int $support_read
 * @property string $support_text
 * @property int $support_user_id
 */
class Support extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%support}}';
    }
}
