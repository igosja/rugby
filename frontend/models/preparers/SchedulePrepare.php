<?php

// TODO refactor

namespace frontend\models\preparers;

use frontend\models\queries\ScheduleQuery;
use yii\data\ActiveDataProvider;

/**
 * Class SchedulePrepare
 * @package frontend\models\preparers
 */
class SchedulePrepare
{
    /**
     * @param int $seasonId
     * @return ActiveDataProvider
     */
    public static function getScheduleDataProvider(int $seasonId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'query' => ScheduleQuery::getScheduleListQuery($seasonId),
        ]);
    }
}
