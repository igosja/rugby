<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionNationalVote
 * @package common\models\db
 *
 * @property int $election_national_vote_id
 * @property int $election_national_vote_application_id
 * @property int $election_national_vote_date
 * @property int $election_national_vote_user_id
 */
class ElectionNationalVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vote}}';
    }
}
