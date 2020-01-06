<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;

/**
 * Class TeamVisitor
 * @package common\models\db
 *
 * @property int $team_visitor_id
 * @property int $team_visitor_team_id
 * @property int $team_visitor_visitor
 */
class TeamVisitor extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%team_visitor}}';
    }
}
