<?php

namespace frontend\controllers;

use common\models\db\Player;
use common\models\db\Season;
use frontend\components\AbstractController;
use frontend\models\preparers\LineupPrepare;
use frontend\models\queries\PlayerQuery;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class PlayerController
 * @package frontend\controllers
 */
class PlayerController extends AbstractController
{
    /**
     *
     */
    public function actionIndex()
    {
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $player = $this->getPlayer($id);

        $seasonId = Yii::$app->request->get('season_id', $this->season->season_id);
        $dataProvider = LineupPrepare::getPlayerDataProvider($id, $seasonId);

        $this->seoTitle($player->playerName() . ' - profile');
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'player' => $player,
            'seasonArray' => Season::getSeasonArray(),
            'seasonId' => $seasonId,
        ]);
    }

    /**
     * @param int $playerId
     * @return Player
     * @throws NotFoundHttpException
     */
    private function getPlayer(int $playerId): Player
    {
        $player = PlayerQuery::getPlayerById($playerId);
        $this->notFound($player);

        return $player;
    }
}
