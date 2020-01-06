<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionNationalViceVote
 * @package common\models\db
 *
 * @property int $election_national_vice_vote_id
 * @property int $election_national_vice_vote_application_id
 * @property int $election_national_vice_vote_date
 * @property int $election_national_vice_vote_user_id
 */
class ElectionNationalViceVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_vice_vote}}';
    }
}
