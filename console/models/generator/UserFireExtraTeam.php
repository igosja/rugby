<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\FireReason;
use common\models\db\History;
use common\models\db\HistoryText;
use common\models\db\Team;
use common\models\db\UserHoliday;
use Exception;

/**
 * Class UserFireExtraTeam
 * @package console\models\generator
 */
class UserFireExtraTeam
{
    /**
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        $userArray = Team::find()
            ->joinWith(['user'])
            ->andWhere([
                'not', ['user_id' => UserHoliday::find()->where(['date_end' => null])]
            ])
            ->andWhere(['!=', 'user_id', 0])
            ->andWhere(['<', 'date_vip', time()])
            ->groupBy(['user_id'])
            ->having(['>', 'COUNT(team.id)', 1])
            ->orderBy(['team.id' => SORT_ASC])
            ->each();
        foreach ($userArray as $user) {
            /**
             * @var Team $user
             */
            $limit = count($user->user->teams) - 1;

            $teamArray = Team::find()
                ->join(
                    'LEFT JOIN',
                    History::tableName(),
                    '`team_id`=`team`.`id` AND `history`.`user_id`=`team`.`user_id`'
                )
                ->where([
                    'team.user_id' => $user->user_id,
                    'history_text_id' => HistoryText::USER_MANAGER_TEAM_IN
                ])
                ->groupBy(['team.id'])
                ->orderBy('MAX(history.id) DESC')
                ->limit($limit)
                ->all();
            foreach ($teamArray as $team) {
                $team->managerFire(FireReason::FIRE_REASON_EXTRA_TEAM);
            }
        }

        $userArray = Team::find()
            ->joinWith(['user'])
            ->andWhere([
                'not', ['user_id' => UserHoliday::find()->where(['date_end' => null])]
            ])
            ->andWhere(['!=', 'user_id', 0])
            ->andWhere(['>=', 'date_vip', time()])
            ->groupBy(['user_id'])
            ->having(['>', 'COUNT(team.id)', 2])
            ->orderBy(['team.id' => SORT_ASC])
            ->each();
        foreach ($userArray as $user) {
            /**
             * @var Team $user
             */
            $limit = count($user->user->teams) - 2;

            $teamArray = Team::find()
                ->join(
                    'LEFT JOIN',
                    History::tableName(),
                    '`team_id`=`team`.`id` AND `history`.`user_id`=`team`.`user_id`'
                )
                ->where([
                    'team.user_id' => $user->user_id,
                    'history_text_id' => HistoryText::USER_MANAGER_TEAM_IN
                ])
                ->groupBy(['team.id'])
                ->orderBy('MAX(history.id) DESC')
                ->limit($limit)
                ->all();
            foreach ($teamArray as $team) {
                $team->managerFire(FireReason::FIRE_REASON_EXTRA_TEAM);
            }
        }
    }
}
