<?php

namespace backend\models\queries;

use common\models\db\Complaint;

/**
 * Class ComplaintQuery
 * @package backend\models\queries
 */
class ComplaintQuery
{
    /**
     * @return int
     */
    public static function countNew(): int
    {
        return Complaint::find()
            ->andWhere(['ready' => null])
            ->count();
    }
}
