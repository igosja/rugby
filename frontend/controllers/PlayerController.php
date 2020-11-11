<?php

namespace frontend\controllers;

use common\models\db\Player;
use common\models\db\Position;
use common\models\db\Season;
use frontend\models\preparers\LineupPrepare;
use frontend\models\queries\PlayerQuery;
use frontend\models\search\PlayerSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class PlayerController
 * @package frontend\controllers
 */
class PlayerController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new PlayerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $countryArray = ArrayHelper::map(
            Player::find()
                ->joinWith([
                    'country',
                ])
                ->groupBy(['country_id'])
                ->orderBy(['country.name' => SORT_ASC])
                ->all(),
            'country.id',
            'country.name'
        );

        $positionArray = ArrayHelper::map(
            Position::find()
                ->orderBy(['id' => SORT_ASC])
                ->all(),
            'id',
            'name'
        );

        $this->setSeoTitle('Список игроков');
        return $this->render('index', [
            'countryArray' => $countryArray,
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
            'positionArray' => $positionArray,
        ]);
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

        $this->setSeoTitle($player->playerName() . ' - profile');
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
