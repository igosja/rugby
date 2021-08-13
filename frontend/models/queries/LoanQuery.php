<?php

// TODO refactor

namespace frontend\models\queries;

use common\models\db\Loan;
use yii\db\ActiveQuery;

/**
 * Class AttitudeQuery
 * @package frontend\models\queries
 */
class LoanQuery
{
    /**
     * @param int $teamId
     * @return ActiveQuery
     */
    public static function getTeamBuyerTransferList(int $teamId): ActiveQuery
    {
        return Loan::find()
            ->where(['team_buyer_id' => $teamId])
            ->andWhere(['not', ['ready' => null]])
            ->orderBy(['ready' => SORT_DESC]);
    }

    /**
     * @param int $teamId
     * @return ActiveQuery
     */
    public static function getTeamSellerTransferList(int $teamId): ActiveQuery
    {
        return Loan::find()
            ->where(['team_seller_id' => $teamId])
            ->andWhere(['not', ['ready' => null]])
            ->orderBy(['ready' => SORT_DESC]);
    }
}
