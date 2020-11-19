<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Season;
use common\models\db\Team;
use common\models\db\UserRating;
use Exception;

/**
 * Class UserToRating
 * @package console\models\generator
 */
class UserToRating
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $seasonId = Season::getCurrentSeason();

        $teamArray = Team::find()
            ->where(['!=', 'user_id', 0])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            $check = UserRating::find()->where([
                'user_id' => $team->user_id,
                'season_id' => null,
            ])->count();

            if (!$check) {
                $model = new UserRating();
                $model->user_id = $team->user_id;
                $model->save();
            }

            $check = UserRating::find()->where([
                'user_id' => $team->user_id,
                'season_id' => $seasonId,
            ])->count();

            if (!$check) {
                $model = new UserRating();
                $model->user_id = $team->user_id;
                $model->season_id = $seasonId;
                $model->save();
            }
        }
    }
}
