<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Game;
use common\models\db\Schedule;
use common\models\db\TeamVisitor;
use common\models\db\TournamentType;
use Exception;
use Yii;

/**
 * Class TeamVisitorAfterGame
 * @package console\models\generator
 */
class TeamVisitorAfterGame
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $insertData = [];

        $gameArray = Game::find()
            ->where(['played' => null])
            ->andWhere([
                'schedule_id' => Schedule::find()
                    ->select('id')
                    ->andWhere('FROM_UNIXTIME(`date`, "%Y-%m-%d")=CURDATE()')
                    ->andWhere(['!=', 'tournament_type_id', TournamentType::NATIONAL])
            ])
            ->orderBy(['id' => SORT_ASC])
            ->each();
        foreach ($gameArray as $game) {
            /**
             * @var Game $game
             */
            $homePoints = $game->home_point;
            if ($homePoints > 72) {
                $homePoints = 72;
            }

            $guestPoints = $game->guest_point;
            if ($guestPoints > 72) {
                $guestPoints = 72;
            }

            $homeVisitor = round(95 + ($homePoints - $guestPoints) * 5 / 8);
            $guestVisitor = round(95 + ($guestPoints - $homePoints) * 5 / 8);

            $insertData[] = [$game->home_team_id, $homeVisitor];
            $insertData[] = [$game->guest_team_id, $guestVisitor];
        }

        Yii::$app->db->createCommand()->batchInsert(
            TeamVisitor::tableName(),
            ['team_id', 'visitor'],
            $insertData
        )->execute();
    }
}
