<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class TournamentType
 * @package common\models\db
 *
 * @property int $tournament_type_id
 * @property int $tournament_type_day_type_id
 * @property string $tournament_type_name
 * @property int $tournament_type_visitor
 */
class TournamentType extends AbstractActiveRecord
{
    const NATIONAL = 1;
    const LEAGUE = 2;
    const CHAMPIONSHIP = 3;
    const CONFERENCE = 4;
    const OFF_SEASON = 5;
    const FRIENDLY = 6;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%tournament_type}}';
    }
}
