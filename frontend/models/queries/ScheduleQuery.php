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
            ->select(['id'])
            ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->column();
    }

    /**
     * @param int $id
     * @return Schedule|null
     */
    public static function getScheduleById(int $id): ?Schedule
    {
        return Schedule::find()
            ->andWhere(['id' => $id])
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
            ->andWhere(['season_id' => $seasonId])
            ->orderBy(['date' => SORT_ASC, 'id' => SORT_ASC]);
    }
}
