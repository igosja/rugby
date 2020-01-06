<?php

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
            ->select([
                'rule_date',
                'rule_text',
                'rule_title',
            ])
            ->where(['rule_id' => $id])
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
            ->select([
                'rule_id',
                'rule_title',
            ])
            ->orderBy(['rule_order' => SORT_ASC])
            ->all();
    }
}
