<?php

// TODO refactor

namespace backend\models\queries;

use common\models\db\Support;

/**
 * Class SupportQuery
 * @package backend\models\queries
 */
class SupportQuery
{
    /**
     * @return int
     */
    public static function countNewQuestions(): int
    {
        return Support::find()
            ->andWhere(['is_question' => true, 'read' => null])
            ->count();
    }
}
