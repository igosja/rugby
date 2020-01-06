<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Blacklist
 * @package common\models\db
 *
 * @property int $blacklist_id
 * @property int $blacklist_interlocutor_user_id
 * @property int $blacklist_owner_user_id
 */
class Blacklist extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%blacklist}}';
    }
}
