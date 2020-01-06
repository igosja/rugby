<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class LineupTemplate
 * @package common\models\db
 *
 * @property int $lineup_template_id
 * @property int $lineup_template_captain
 * @property string $lineup_template_name
 * @property int $lineup_template_national_id
 * @property int $lineup_template_player_1
 * @property int $lineup_template_player_2
 * @property int $lineup_template_player_3
 * @property int $lineup_template_player_4
 * @property int $lineup_template_player_5
 * @property int $lineup_template_player_6
 * @property int $lineup_template_player_7
 * @property int $lineup_template_player_8
 * @property int $lineup_template_player_9
 * @property int $lineup_template_player_10
 * @property int $lineup_template_player_11
 * @property int $lineup_template_player_12
 * @property int $lineup_template_player_13
 * @property int $lineup_template_player_14
 * @property int $lineup_template_player_15
 * @property int $lineup_template_rudeness_id
 * @property int $lineup_template_style_id
 * @property int $lineup_template_tactic_id
 * @property int $lineup_template_team_id
 */
class LineupTemplate extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%lineup_template}}';
    }
}
