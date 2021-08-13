<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\FireReason;
use common\models\db\Team;
use common\models\db\UserHoliday;
use common\models\executors\TeamManagerFireExecute;
use Exception;
use Throwable;

/**
 * Class UserFire
 * @package console\models\generator
 */
class UserFire
{
    /**
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        $teamArray = Team::find()
            ->joinWith(['user'])
            ->andWhere([
                'not', ['user_id' => UserHoliday::find()->select(['user_id'])->where(['date_end' => null])]
            ])
            ->andWhere(['!=', 'user_id', 0])
            ->andWhere(['<', 'date_vip', time()])
            ->andWhere(['>=', 'auto_number', Team::MAX_AUTO_GAMES])
            ->orderBy(['team.id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            (new TeamManagerFireExecute($team, FireReason::FIRE_REASON_AUTO))->execute();
        }

        $teamArray = Team::find()
            ->joinWith(['user'])
            ->andWhere([
                'not', ['user_id' => UserHoliday::find()->select(['user_id'])->where(['date_end' => null])]
            ])
            ->andWhere(['!=', 'user_id', 0])
            ->andWhere(['<', 'date_vip', time()])
            ->andWhere(['<', 'date_login', time() - 1296000])//15 days
            ->orderBy(['team.id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            (new TeamManagerFireExecute($team, FireReason::FIRE_REASON_ABSENCE))->execute();
        }

        $teamArray = Team::find()
            ->joinWith(['user'])
            ->andWhere([
                'not', ['user_id' => UserHoliday::find()->select(['user_id'])->where(['date_end' => null])]
            ])
            ->andWhere(['!=', 'user_id', 0])
            ->andWhere(['>=', 'date_vip', time()])
            ->andWhere(['<', 'date_login', time() - 5184000])//60 days
            ->orderBy(['team.id' => SORT_ASC])
            ->each();
        foreach ($teamArray as $team) {
            /**
             * @var Team $team
             */
            (new TeamManagerFireExecute($team, FireReason::FIRE_REASON_ABSENCE))->execute();
        }
    }
}
