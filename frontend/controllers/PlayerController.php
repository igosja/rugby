<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\AchievementPlayer;
use common\models\db\History;
use common\models\db\Loan;
use common\models\db\Player;
use common\models\db\Position;
use common\models\db\Season;
use common\models\db\Squad;
use common\models\db\Transfer;
use frontend\models\forms\LoanApplicationFrom;
use frontend\models\forms\LoanApplicationTo;
use frontend\models\forms\LoanFrom;
use frontend\models\forms\LoanTo;
use frontend\models\forms\TransferApplicationFrom;
use frontend\models\forms\TransferApplicationTo;
use frontend\models\forms\TransferFrom;
use frontend\models\forms\TransferTo;
use frontend\models\preparers\LineupPrepare;
use frontend\models\queries\PlayerQuery;
use frontend\models\search\PlayerSearch;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

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

        $this->setSeoTitle(Yii::t('frontend', 'controllers.player.index.title'));
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

        $seasonId = Yii::$app->request->get('season_id', $this->season->id);
        $dataProvider = LineupPrepare::getPlayerDataProvider($id, $seasonId);

        $this->setSeoTitle($player->playerName() . '. ' . Yii::t('frontend', 'controllers.player.view.title'));
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'player' => $player,
            'seasonArray' => Season::getSeasonArray(),
            'seasonId' => $seasonId,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionHistory(int $id): string
    {
        $player = $this->getPlayer($id);

        $query = History::find()
            ->where(['player_id' => $id])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle($player->playerName() . '. ' . Yii::t('frontend', 'controllers.player.history.title'));

        return $this->render('history', [
            'dataProvider' => $dataProvider,
            'player' => $player,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDeal(int $id): string
    {
        $player = $this->getPlayer($id);

        $query = Loan::find()
            ->andWhere(['player_id' => $id])
            ->andWhere(['not', ['ready' => null]])
            ->orderBy(['ready' => SORT_DESC]);
        $dataProviderLoan = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $query = Transfer::find()
            ->andWhere(['player_id' => $id])
            ->andWhere(['not', ['ready' => null]])
            ->orderBy(['ready' => SORT_DESC]);
        $dataProviderTransfer = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle($player->playerName() . '. ' . Yii::t('frontend', 'controllers.player.deal.title'));

        return $this->render('deal', [
            'dataProviderLoan' => $dataProviderLoan,
            'dataProviderTransfer' => $dataProviderTransfer,
            'player' => $player,
        ]);
    }

    /**
     * @param int $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionTransfer(int $id)
    {
        $player = $this->getPlayer($id);
        $onTransfer = (bool)$player->transfer;

        $formConfig = ['player' => $player, 'team' => $this->myTeam];

        $modelTransferTo = new TransferTo($formConfig);
        $modelTransferFrom = new TransferFrom($formConfig);
        $modelTransferApplicationTo = new TransferApplicationTo($formConfig);
        $modelTransferApplicationFrom = new TransferApplicationFrom($formConfig);
        if ($player->myPlayer()) {
            if ($modelTransferTo->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelTransferTo);
                }

                try {
                    $modelTransferTo->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }

            if ($modelTransferFrom->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelTransferFrom);
                }

                try {
                    $modelTransferFrom->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }
        } else {
            if ($modelTransferApplicationTo->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelTransferApplicationTo);
                }

                try {
                    $modelTransferApplicationTo->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }

            if ($modelTransferApplicationFrom->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelTransferApplicationTo);
                }

                try {
                    $modelTransferApplicationFrom->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.player.transfer.title'));

        return $this->render('transfer', [
            'modelTransferApplicationFrom' => $modelTransferApplicationFrom,
            'modelTransferApplicationTo' => $modelTransferApplicationTo,
            'modelTransferFrom' => $modelTransferFrom,
            'modelTransferTo' => $modelTransferTo,
            'onTransfer' => $onTransfer,
            'player' => $player,
        ]);
    }

    /**
     * @param int $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionLoan(int $id)
    {
        $player = $this->getPlayer($id);
        $onLoan = $player->loan ? true : false;

        $formConfig = ['player' => $player, 'team' => $this->myTeam];

        $modelLoanTo = new LoanTo($formConfig);
        $modelLoanFrom = new LoanFrom($formConfig);
        $modelLoanApplicationTo = new LoanApplicationTo($formConfig);
        $modelLoanApplicationFrom = new LoanApplicationFrom($formConfig);
        if ($player->myPlayer()) {
            if ($modelLoanTo->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelLoanTo);
                }

                try {
                    $modelLoanTo->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }

            if ($modelLoanFrom->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelLoanFrom);
                }

                try {
                    $modelLoanFrom->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }
        } else {
            if ($modelLoanApplicationTo->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelLoanApplicationTo);
                }

                try {
                    $modelLoanApplicationTo->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }

            if ($modelLoanApplicationFrom->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($modelLoanApplicationTo);
                }

                try {
                    $modelLoanApplicationFrom->execute();
                } catch (Throwable $e) {
                    ErrorHelper::log($e);
                }
                return $this->refresh();
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.player.loan.title'));

        return $this->render('loan', [
            'modelLoanApplicationFrom' => $modelLoanApplicationFrom,
            'modelLoanApplicationTo' => $modelLoanApplicationTo,
            'modelLoanFrom' => $modelLoanFrom,
            'modelLoanTo' => $modelLoanTo,
            'onLoan' => $onLoan,
            'player' => $player,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAchievement(int $id): string
    {
        $player = $this->getPlayer($id);

        $query = AchievementPlayer::find()
            ->where(['player_id' => $id])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle($player->playerName() . '. ' . Yii::t('frontend', 'controllers.player.achievement.title'));

        return $this->render('achievement', [
            'dataProvider' => $dataProvider,
            'player' => $player,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTrophy(int $id): string
    {
        $player = $this->getPlayer($id);

        $query = AchievementPlayer::find()
            ->where(['player_id' => $id, 'place' => [0, 1], 'stage_id' => null])
            ->orderBy(['id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
        ]);

        $this->setSeoTitle($player->playerName() . '. ' . Yii::t('frontend', 'controllers.player.trophy.title'));

        return $this->render('trophy', [
            'dataProvider' => $dataProvider,
            'player' => $player,
        ]);
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionSquad($id): bool
    {
        if (!$this->myTeam) {
            return false;
        }

        $player = Player::find()
            ->where(['id' => $id, 'team_id' => $this->myTeam->id])
            ->limit(1)
            ->one();
        $this->notFound($player);

        $player->squad_id = Yii::$app->request->get('squad', Squad::SQUAD_DEFAULT);
        return $player->save(true, ['squad_id']);
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionNationalSquad($id): bool
    {
        if (!$this->myNational) {
            return false;
        }

        $player = Player::find()
            ->where(['id' => $id, 'national_id' => $this->myNational->id])
            ->limit(1)
            ->one();
        $this->notFound($player);

        $player->national_squad_id = Yii::$app->request->get('squad', Squad::SQUAD_DEFAULT);
        return $player->save(true, ['national_squad_id']);
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
