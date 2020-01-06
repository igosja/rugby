<?php

namespace frontend\models\queries;

use common\models\db\Schedule;
use yii\db\ActiveQuery;

/**
 * Class ScheduleQuery
 * @package frontend\models\queries
 */
class ScheduleQuery
{
    /**
     * @return array
     */
    public static function getCurrentScheduleIds(): array
    {
        return Schedule::find()
            ->select(['schedule_id'])
            ->where('FROM_UNIXTIME(`schedule_date`, "%Y-%m-%d")=CURDATE()')
            ->column();
    }

    /**
     * @param int $id
     * @return Schedule|null
     */
    public static function getScheduleById(int $id)
    {
        return Schedule::find()
            ->with([
                'stage' => function (ActiveQuery $query) {
                    $query->select([
                        'stage_id',
                        'stage_name',
                    ]);
                },
                'tournamentType' => function (ActiveQuery $query) {
                    $query->select([
                        'tournament_type_id',
                        'tournament_type_name',
                    ]);
                },
            ])
            ->select([
                'schedule_date',
                'schedule_id',
                'schedule_season_id',
                'schedule_stage_id',
                'schedule_tournament_type_id',
            ])
            ->where(['schedule_id' => $id])
            ->limit(1)
            ->one();
    }

    /**
     * @param int $seasonId
     * @return ActiveQuery
     */
    public static function getScheduleListQuery(int $seasonId): ActiveQuery
    {
        return Schedule::find()
            ->with([
                'stage' => function (ActiveQuery $query) {
                    $query->select([
                        'stage_id',
                        'stage_name',
                    ]);
                },
                'tournamentType' => function (ActiveQuery $query) {
                    $query->select([
                        'tournament_type_id',
                        'tournament_type_name',
                    ]);
                },
            ])
            ->select([
                'schedule_date',
                'schedule_id',
                'schedule_stage_id',
                'schedule_tournament_type_id',
            ])
            ->where(['schedule_season_id' => $seasonId])
            ->orderBy(['schedule_date' => SORT_ASC, 'schedule_id' => SORT_ASC]);
    }
}
