<?php

namespace common\models\db;

use common\components\AbstractActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class Schedule
 * @package common\models\db
 *
 * @property int $schedule_id
 * @property int $schedule_date
 * @property int $schedule_season_id
 * @property int $schedule_stage_id
 * @property int $schedule_tournament_type_id
 *
 * @property Stage $stage
 * @property TournamentType $tournamentType
 */
class Schedule extends AbstractActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%schedule}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getStage(): ActiveQuery
    {
        return $this->hasOne(Stage::class, ['stage_id' => 'schedule_stage_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTournamentType(): ActiveQuery
    {
        return $this->hasOne(TournamentType::class, ['tournament_type_id' => 'schedule_tournament_type_id']);
    }
}
