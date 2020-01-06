<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class Event
 * @package common\models\db
 *
 * @property int $event_id
 * @property int $event_event_text_goal_id
 * @property int $event_event_type_id
 * @property int $event_game_id
 * @property int $event_guest_score
 * @property int $event_home_score
 * @property int $event_minute
 * @property int $event_national_id
 * @property int $event_player_score_id
 * @property int $event_team_id
 */
class Event extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%event}}';
    }
}
