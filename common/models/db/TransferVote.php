<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class TransferVote
 * @package common\models\db
 *
 * @property int $transfer_vote_id
 * @property int $transfer_vote_transfer_id
 * @property int $transfer_vote_rating
 * @property int $transfer_vote_user_id
 */
class TransferVote extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%transfer_vote}}';
    }
}
