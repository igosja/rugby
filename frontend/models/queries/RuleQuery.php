<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Rule;

/**
 * Class RuleQuery
 * @package frontend\models\queries
 */
class RuleQuery
{
    /**
     * @param int $id
     * @return Rule|null
     */
    public static function getRuleById(int $id): ?Rule
    {
        /**
         * @var Rule $result
         */
        $result = Rule::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        return $result;
    }

    /**
     * @return Rule[]
     */
    public static function getRuleList(): array
    {
        return Rule::find()
            ->orderBy(['order' => SORT_ASC])
            ->all();
    }
}
