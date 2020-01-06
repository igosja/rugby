<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionNationalApplication
 * @package common\models\db
 *
 * @property int $election_national_application_id
 * @property int $election_national_application_date
 * @property int $election_national_application_election_id
 * @property string $election_national_application_text
 * @property int $election_national_application_user_id
 */
class ElectionNationalApplication extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_application}}';
    }
}
