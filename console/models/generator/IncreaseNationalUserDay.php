<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\National;
use common\models\db\NationalUserDay;
use Exception;

/**
 * Class IncreaseNationalUserDay
 * @package console\models\generator
 */
class IncreaseNationalUserDay
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $nationalArray = National::find()
            ->where(['not', ['user_id' => null]])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($nationalArray as $national) {
            /**
             * @var National $national
             */
            if ($national->user_id) {
                $model = NationalUserDay::find()
                    ->where([
                        'national_id' => $national->id,
                        'user_id' => $national->user_id,
                    ])
                    ->limit(1)
                    ->one();
                if (!$model) {
                    $model = new NationalUserDay();
                    $model->day = 0;
                    $model->national_id = $national->id;
                    $model->user_id = $national->user_id;
                }

                $model->day += 2;
                $model->save();
            }

            if ($national->vice_user_id) {
                $model = NationalUserDay::find()
                    ->where([
                        'national_id' => $national->id,
                        'user_id' => $national->vice_user_id,
                    ])
                    ->limit(1)
                    ->one();
                if (!$model) {
                    $model = new NationalUserDay();
                    $model->day = 0;
                    $model->national_id = $national->id;
                    $model->user_id = $national->vice_user_id;
                }

                $model->day++;
                $model->save();
            }
        }
    }
}
