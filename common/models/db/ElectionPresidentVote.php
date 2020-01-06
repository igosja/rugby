<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionPresidentVote
 * @package common\models\db
 *
 * @property int $election_president_vote_id
 * @property int $election_president_vote_application_id
 * @property int $election_president_vote_date
 * @property int $election_president_vote_user_id
 */
class ElectionPresidentVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_president_vote}}';
    }
}
