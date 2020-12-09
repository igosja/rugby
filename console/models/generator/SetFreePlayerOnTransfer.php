<?php

// TODO refactor

namespace console\models\generator;

use common\models\db\Player;
use common\models\db\PlayerPosition;
use common\models\db\Position;
use common\models\db\Season;
use common\models\db\Transfer;
use Exception;

/**
 * Class SetFreePlayerOnTransfer
 * @package console\models\generator
 */
class SetFreePlayerOnTransfer
{
    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $positionArray = [
            [Position::POS_01, 5],
            [Position::POS_02, 5],
            [Position::POS_03, 5],
            [Position::POS_04, 5],
            [Position::POS_05, 5],
            [Position::POS_06, 5],
            [Position::POS_07, 5],
            [Position::POS_08, 5],
            [Position::POS_09, 5],
            [Position::POS_10, 5],
            [Position::POS_11, 5],
            [Position::POS_12, 5],
            [Position::POS_13, 5],
            [Position::POS_14, 5],
            [Position::POS_15, 5],
        ];

        foreach ($positionArray as $item) {
            $check = Transfer::find()
                ->joinWith(['player'])
                ->where([
                    'team_seller_id' => 0,
                    'ready' => null,
                    'player.id' => PlayerPosition::find()->select(['player_id'])->where(['position_id' => $item[0]]),
                ])
                ->count();
            for ($i = 0; $i < $item[1] - $check; $i++) {
                /**
                 * @var Player $player
                 */
                $player = Player::find()
                    ->joinWith(['transfer'])
                    ->where([
                        'team_id' => 0,
                        'loan_team_id' => null,
                        'player.id' => PlayerPosition::find()->select(['player_id'])->where(['position_id' => $item[0]]),
                        'transfer.id' => null,
                    ])
                    ->andWhere(['<', 'player.age', Player::AGE_READY_FOR_PENSION])
                    ->andWhere(['=', 'school_team_id', 0])
                    ->orderBy('RAND()')
                    ->one();
                if (!$player) {
                    continue;
                }

                $model = new Transfer();
                $model->player_id = $player->id;
                $model->price_seller = ceil($player->price / 2);
                $model->season_id = Season::getCurrentSeason();
                $model->team_seller_id = 0;
                $model->user_seller_id = 0;
                $model->save();
            }
        }
    }
}
