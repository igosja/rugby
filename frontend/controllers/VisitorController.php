<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\Division;
use common\models\db\Game;
use common\models\db\Special;
use common\models\db\TournamentType;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * Class VisitorController
 * @package frontend\controllers
 */
class VisitorController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView(int $id)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $game = $this->getGame($id);

        $special = 0;
        foreach ($game->lineups as $lineup) {
            if (!$lineup->player) {
                continue;
            }
            foreach ($lineup->player->playerSpecials as $playerSpecial) {
                if (Special::IDOL === $playerSpecial->special_id) {
                    $special += $playerSpecial->level;
                }
            }
        }

        $guestVisitor = $game->guestTeam->visitor;
        $homeVisitor = $game->homeTeam->visitor;
        $stadiumCapacity = $game->stadium->capacity;
        $stageVisitor = $game->schedule->stage->visitor;
        $tournamentTypeId = $game->schedule->tournament_type_id;
        $tournamentTypeVisitor = $game->schedule->tournamentType->visitor;
        $divisionId = Division::D1;
        if (TournamentType::CHAMPIONSHIP === $tournamentTypeId) {
            $divisionId = $game->homeTeam->championship->division_id;
        }

        $gameVisitor = $stadiumCapacity;
        $gameVisitor *= $tournamentTypeVisitor;
        $gameVisitor *= $stageVisitor;
        $gameVisitor *= (100 - ($divisionId - 1));

        $visitor_array = [];

        for ($i = 10; $i <= 50; $i++) {
            $visitor = $gameVisitor / ((($i - Game::TICKET_PRICE_BASE) / 10) ** 1.1);

            if (in_array($tournamentTypeId, [TournamentType::FRIENDLY, TournamentType::NATIONAL], true)) {
                $visitor = $visitor * ($homeVisitor + $guestVisitor) / 2;
            } else {
                $visitor = $visitor * ($homeVisitor * 2 + $guestVisitor) / 3;
            }

            $visitor *= (100 + $special * 5);
            $visitor /= 10000000000;
            $visitor = round($visitor);

            if ($visitor > $stadiumCapacity) {
                $visitor = $stadiumCapacity;
            }

            $visitor_array['visitor'][$i] = $visitor;
            $visitor_array['income'][$i] = $visitor * $i;
        }

        $x_data = array_keys($visitor_array['visitor']);
        $s_data_visitor = array_values($visitor_array['visitor']);
        $s_data_income = array_values($visitor_array['income']);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.visitor.view.title'));

        return $this->render('view', [
            'game' => $game,
            'sDataIncome' => $s_data_income,
            'sDataVisitor' => $s_data_visitor,
            'special' => $special,
            'xData' => $x_data,
        ]);
    }

    /**
     * @param int $id
     * @return Game
     * @throws NotFoundHttpException
     */
    public function getGame(int $id): Game
    {
        /**
         * @var Game $game
         */
        $game = Game::find()
            ->where(['id' => $id, 'played' => null])
            ->andWhere([
                'or',
                ['guest_team_id' => $this->myTeamOrVice->id],
                ['home_team_id' => $this->myTeamOrVice->id]
            ])
            ->limit(1)
            ->one();
        $this->notFound($game);

        return $game;
    }
}
