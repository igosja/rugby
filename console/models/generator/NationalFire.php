<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Attitude;
use common\models\db\FireReason;
use common\models\db\National;
use common\models\db\NationalType;
use common\models\db\Team;
use yii\db\Exception;

/**
 * Class NationalFire
 * @package console\models\generator
 */
class NationalFire
{
    /**
     * @return void
     * @throws Exception
     * @throws \Exception
     */
    public function execute()
    {
        $nationalArray = National::find()
            ->andWhere(['not', ['user_id' => null]])
            ->andWhere(['not', ['vice_user_id' => null]])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($nationalArray as $national) {
            /**
             * @var National $national
             */
            if (NationalType::MAIN === $national->national_type_id) {
                $attitudeField = 'national_attitude_id';
            } elseif (NationalType::U21 === $national->national_type_id) {
                $attitudeField = 'u21_attitude_id';
            } else {
                $attitudeField = 'u19_attitude_id';
            }
            $negative = Team::find()
                ->joinWith(['stadium.city.country.federation'])
                ->where(['!=', 'user_id', 0])
                ->andWhere([
                    $attitudeField => Attitude::NEGATIVE,
                    'federation.id' => $national->federation_id,
                ])
                ->count();
            $positive = Team::find()
                ->joinWith(['stadium.city.country.federation'])
                ->where(['!=', 'user_id', 0])
                ->andWhere([
                    $attitudeField => Attitude::POSITIVE,
                    'federation.id' => $national->federation_id,
                ])
                ->count();

            $total = Team::find()
                ->joinWith(['stadium.city.country.federation'])
                ->where(['!=', 'user_id', 0])
                ->andWhere(['federation.id' => $national->federation_id])
                ->count();

            $percentNegative = round(($negative ?: 0) / ($total ?: 1) * 100);
            $percentPositive = round(($positive ?: 0) / ($total ?: 1) * 100);

            if ($percentNegative > 25 && $percentPositive < 50) {
                $national->fireUser(FireReason::FIRE_REASON_VOTE);
            }
        }
    }
}
