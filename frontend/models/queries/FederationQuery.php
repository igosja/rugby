<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Federation;
use yii\db\ActiveQuery;

/**
 * Class FederationQuery
 * @package frontend\models\queries
 */
class FederationQuery
{
    /**
     * @param int $id
     * @return Federation|null
     */
    public static function getFederationById(int $id): ?Federation
    {
        return Federation::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
    }
}
