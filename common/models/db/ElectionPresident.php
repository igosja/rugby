<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionPresident
 * @package common\models\db
 *
 * @property int $election_president_id
 * @property int $election_president_country_id
 * @property int $election_president_date
 * @property int $election_president_election_status_id
 */
class ElectionPresident extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president}}';
    }
}
