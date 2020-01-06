<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionNationalViceApplication
 * @package common\models\db
 *
 * @property int $election_national_vice_application_id
 * @property int $election_national_vice_application_date
 * @property int $election_national_vice_application_election_id
 * @property string $election_national_vice_application_text
 * @property int $election_national_vice_application_user_id
 */
class ElectionNationalViceApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vice_application}}';
    }
}
