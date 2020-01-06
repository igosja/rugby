<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionPresidentApplication
 * @package common\models\db
 *
 * @property int $election_president_application_id
 * @property int $election_president_application_date
 * @property int $election_president_application_election_id
 * @property string $election_president_application_text
 * @property int $election_president_application_user_id
 */
class ElectionPresidentApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_application}}';
    }
}
