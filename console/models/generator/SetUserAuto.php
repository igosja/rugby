<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\Team;

/**
 * Class SetUserAuto
 * @package console\models\generator
 */
class SetUserAuto
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Team::updateAllCounters(
            ['auto_number' => 1],
            [
                'and',
                [
                    'id' => Game::find()
                        ->select(['home_team_id'])
                        ->where(['played' => null, 'home_auto' => true])
                        ->andWhere([
                            'schedule_id' => Schedule::find()
                                ->select('id')
                                ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                        ]),
                ],
                ['!=', 'user_id', 0]
            ]
        );

        Team::updateAllCounters(
            ['auto_number' => 1],
            [
                'and',
                [
                    'id' => Game::find()
                        ->select(['guest_team_id'])
                        ->where(['played' => null, 'guest_auto' => true])
                        ->andWhere([
                            'schedule_id' => Schedule::find()
                                ->select('id')
                                ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                        ]),
                ],
                ['!=', 'user_id', 0]
            ]
        );

        Team::updateAll(
            ['auto_number' => 0],
            [
                'id' => Game::find()
                    ->select(['home_team_id'])
                    ->where(['played' => null, 'home_auto' => false])
                    ->andWhere([
                        'schedule_id' => Schedule::find()
                            ->select('id')
                            ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                    ])
            ]
        );

        Team::updateAll(
            ['auto_number' => 0],
            [
                'id' => Game::find()
                    ->select(['guest_team_id'])
                    ->where(['played' => null, 'guest_auto' => false])
                    ->andWhere([
                        'schedule_id' => Schedule::find()
                            ->select('id')
                            ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                    ])
            ]
        );

        Team::updateAll(['auto_number' => 5], ['>', 'auto_number', 5]);
        Team::updateAll(['auto_number' => 0], ['user_id' => 0]);
    }
}