<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ElectionNationalPlayer
 * @package common\models\db
 *
 * @property int $election_national_player_id
 * @property int $election_national_player_application_id
 * @property int $election_national_player_player_id
 */
class ElectionNationalPlayer extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%election_national_player}}';
    }
}
