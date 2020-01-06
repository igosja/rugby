<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionNationalVice
 * @package common\models\db
 *
 * @property int $election_national_vice_id
 * @property int $election_national_vice_country_id
 * @property int $election_national_vice_date
 * @property int $election_national_vice_election_status_id
 * @property int $election_national_vice_national_type_id
 */
class ElectionNationalVice extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vice}}';
    }
}
