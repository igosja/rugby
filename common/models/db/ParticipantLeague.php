<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class ParticipantLeague
 * @package common\models\db
 *
 * @property int $participant_league_id
 * @property int $participant_league_season_id
 * @property int $participant_league_stage_1
 * @property int $participant_league_stage_2
 * @property int $participant_league_stage_4
 * @property int $participant_league_stage_8
 * @property int $participant_league_stage_id
 * @property int $participant_league_stage_in
 * @property int $participant_league_team_id
 */
class ParticipantLeague extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%participant_league}}';
    }
}
