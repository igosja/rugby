<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionPresidentViceVote
 * @package common\models\db
 *
 * @property int $election_president_vice_vote_id
 * @property int $election_president_vice_vote_application_id
 * @property int $election_president_vice_vote_date
 * @property int $election_president_vice_vote_user_id
 */
class ElectionPresidentViceVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_vice_vote}}';
    }
}
