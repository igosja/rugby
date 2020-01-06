<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionNational
 * @package common\models\db
 *
 * @property int $election_national_id
 * @property int $election_national_country_id
 * @property int $election_national_date
 * @property int $election_national_election_status_id
 * @property int $election_national_national_type_id
 */
class ElectionNational extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national}}';
    }
}
