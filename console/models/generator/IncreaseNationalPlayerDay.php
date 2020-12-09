<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Lineup;
use common\models\db\NationalPlayerDay;
use Exception;

/**
 * Class IncreaseNationalPlayerDay
 * @package console\models\generator
 */
class IncreaseNationalPlayerDay
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $lineupArray = Lineup::find()
            ->joinWith(['game.schedule'])
            ->where(['not', ['national_id' => null]])
            ->andWhere(['not', ['player_id' => null]])
            ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
            ->orderBy(['lineup.id' => SORT_ASC])
            ->each();
        foreach ($lineupArray as $lineup) {
            /**
             * @var Lineup $lineup
             */
            if ($lineup->player->team_id) {
                $model = NationalPlayerDay::find()
                    ->where([
                        'national_id' => $lineup->national_id,
                        'player_id' => $lineup->player_id,
                        'team_id' => $lineup->player->team_id,
                    ])
                    ->limit(1)
                    ->one();
                if (!$model) {
                    $model = new NationalPlayerDay();
                    $model->day = 0;
                    $model->national_id = $lineup->national_id;
                    $model->player_id = $lineup->player_id;
                    $model->team_id = $lineup->player->team_id;
                }

                $model->day++;
                $model->save();
            }
        }
    }
}
