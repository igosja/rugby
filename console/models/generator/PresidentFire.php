<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Attitude;
use common\models\db\Federation;
use common\models\db\FireReason;
use common\models\db\Team;
use yii\db\Exception;

/**
 * Class PresidentFire
 * @package console\models\generator
 */
class PresidentFire
{
    /**
     * @throws \Exception
     * @throws Exception
     */
    public function execute(): void
    {
        $federationArray = Federation::find()
            ->where(['!=', 'president_user_id', 0])
            ->andWhere(['!=', 'vice_user_id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($federationArray as $federation) {
            /**
             * @var Federation $federation
             */
            $negative = Team::find()
                ->joinWith(['stadium.city.country.federation'])
                ->where(['!=', 'user_id', 0])
                ->andWhere([
                    'president_attitude_id' => Attitude::NEGATIVE,
                    'federation.id' => $federation->id,
                ])
                ->count();
            $positive = Team::find()
                ->joinWith(['stadium.city.country.federation'])
                ->where(['!=', 'user_id', 0])
                ->andWhere([
                    'president_attitude_id' => Attitude::POSITIVE,
                    'federation.id' => $federation->id,
                ])
                ->count();

            $total = Team::find()
                ->joinWith(['stadium.city.country.federation'])
                ->where(['!=', 'user_id', 0])
                ->andWhere(['federation.id' => $federation->id])
                ->count();

            $percentNegative = round(($negative ?: 0) / ($total ?: 1) * 100);
            $percentPositive = round(($positive ?: 0) / ($total ?: 1) * 100);

            if ($percentNegative > 25 && $percentPositive < 50) {
                $federation->firePresident(FireReason::FIRE_REASON_VOTE);
            }
        }
    }
}
