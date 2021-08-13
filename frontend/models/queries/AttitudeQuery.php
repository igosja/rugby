<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Attitude;

/**
 * Class AttitudeQuery
 * @package frontend\models\queries
 */
class AttitudeQuery
{
    /**
     * @return Attitude[]
     */
    public static function getAttitudeList(): array
    {
        return Attitude::find()
            ->orderBy(['order' => SORT_ASC])
            ->all();
    }
}
