<?php

namespace frontend\models\queries;

use common\models\db\National;

/**
 * Class NationalQuery
 * @package frontend\models\queries
 */
class NationalQuery
{
    /**
     * @param int $userId
     * @return array
     */
    public static function getNationalListByUserId(int $userId): array
    {
        return National::find()
            ->select(['id'])
            ->where(
                [
                    'or',
                    ['user_id' => $userId],
                    ['vice_user_id' => $userId],
                ]
            )
            ->all();
    }
}
