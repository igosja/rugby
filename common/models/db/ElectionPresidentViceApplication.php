<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionPresidentViceApplication
 * @package common\models\db
 *
 * @property int $election_president_vice_application_id
 * @property int $election_president_vice_application_date
 * @property int $election_president_vice_application_election_id
 * @property string $election_president_vice_application_text
 * @property int $election_president_vice_application_user_id
 */
class ElectionPresidentViceApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_vice_application}}';
    }
}
