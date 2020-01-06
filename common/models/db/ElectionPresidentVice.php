<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionPresidentVice
 * @package common\models\db
 *
 * @property int $election_president_vice_id
 * @property int $election_president_vice_country_id
 * @property int $election_president_vice_date
 * @property int $election_president_vice_election_status_id
 */
class ElectionPresidentVice extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_vice}}';
    }
}
