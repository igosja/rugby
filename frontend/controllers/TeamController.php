<?php

namespace frontend\controllers;

use common\models\db\Season;
use common\models\db\Team;
use Exception;
use frontend\components\AbstractController;
use frontend\models\preparers\AchievementPrepare;
use frontend\models\preparers\FinancePrepare;
use frontend\models\preparers\GamePrepare;
use frontend\models\preparers\HistoryPrepare;
use frontend\models\preparers\LoanPrepare;
use frontend\models\preparers\PlayerPrepare;
use frontend\models\preparers\TeamPrepare;
use frontend\models\preparers\TransferPrepare;
use frontend\models\queries\TeamQuery;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class TeamController
 * @package frontend\controllers
 */
class TeamController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = TeamPrepare::getTeamGroupDataProvider();

        $this->seoTitle('Команды');
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int|null $id
     * @return string|Response
     * @throws Exception
     */
    public function actionView(int $id = null)
    {
        if (!$id && $this->myTeamOrVice) {
            return $this->redirect(['team/view', 'id' => $this->myTeamOrVice->team_id]);
        }

        if (!$id) {
            return $this->redirect(['team-request/index']);
        }

        $team = $this->getTeam($id);

        $notificationArray = [];
        if ($this->myTeam && $id === $this->myTeam->team_id) {
            $notificationArray = $this->notificationArray();
        }

        $dataProvider = PlayerPrepare::getPlayerTeamDataProvider($team);

        $this->seoTitle($team->fullName() . ' Профиль команды');
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'notificationArray' => $notificationArray,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAchievement(int $id): string
    {
        $team = $this->getTeam($id);

        $dataProvider = AchievementPrepare::getTeamAchievementDataProvider($team->team_id);

        $this->seoTitle($team->fullName() . ' - achievements');
        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDeal(int $id): string
    {
        $team = $this->getTeam($id);

        $dataProviderTransferFrom = TransferPrepare::getTeamSellerDataProvider($team->team_id);
        $dataProviderTransferTo = TransferPrepare::getTeamBuyerDataProvider($team->team_id);
        $dataProviderLoanFrom = LoanPrepare::getTeamSellerDataProvider($team->team_id);
        $dataProviderLoanTo = LoanPrepare::getTeamBuyerDataProvider($team->team_id);

        $this->seoTitle($team->fullName() . ' - deals');
        return $this->render('deal', [
            'dataProviderTransferFrom' => $dataProviderTransferFrom,
            'dataProviderTransferTo' => $dataProviderTransferTo,
            'dataProviderLoanFrom' => $dataProviderLoanFrom,
            'dataProviderLoanTo' => $dataProviderLoanTo,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFinance(int $id): string
    {
        $team = $this->getTeam($id);

        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);
        $dataProvider = FinancePrepare::getTeamDataProvider($team->team_id, $seasonId);

        $this->seoTitle($team->fullName() . ' - finance');
        return $this->render('finance', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGame(int $id): string
    {
        $team = $this->getTeam($id);

        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);
        $dataProvider = GamePrepare::getTeamGameDataProvider($team->team_id, $seasonId);

        $this->seoTitle($team->fullName() . ' - games');
        return $this->render('game', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionHistory(int $id): string
    {
        $team = $this->getTeam($id);

        $seasonId = Yii::$app->request->get('seasonId', $this->season->season_id);
        $dataProvider = HistoryPrepare::getTeamDataProvider($team->team_id, $seasonId);

        $this->seoTitle($team->fullName() . ' - history');
        return $this->render('history', [
            'dataProvider' => $dataProvider,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTrophy(int $id): string
    {
        $team = $this->getTeam($id);

        $dataProvider = AchievementPrepare::getTeamTrophyDataProvider($team->team_id);

        $this->seoTitle($team->fullName() . ' - trophies');
        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
            'team' => $team,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionStatistics(int $id): string
    {
        $team = $this->getTeam($id);

        $team = Team::find()
            ->where(['team_id' => $team->team_id])
            ->limit(1)
            ->one();

        $this->seoTitle($team->fullName() . ' - statistics');
        return $this->render('statistics', [
            'team' => $team,
        ]);
    }

    /**
     * @return array
     */
    private function notificationArray(): array
    {
        return [];
    }

    /**
     * @param int $teamId
     * @return Team
     * @throws NotFoundHttpException
     */
    private function getTeam(int $teamId): Team
    {
        $team = TeamQuery::getTeamById($teamId);
        $this->notFound($team);

        return $team;
    }
}
