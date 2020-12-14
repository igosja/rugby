<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use common\models\db\Loan;
use common\models\db\LoanVote;
use common\models\db\Player;
use common\models\db\Position;
use common\models\db\Transfer;
use common\models\db\TransferApplication;
use common\models\db\TransferComment;
use common\models\db\User;
use common\models\db\UserBlockType;
use common\models\db\UserRole;
use frontend\models\forms\TransferVote;
use frontend\models\search\TransferHistorySearch;
use frontend\models\search\TransferSearch;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class TransferController
 * @package frontend\controllers
 */
class TransferController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $countryArray = ArrayHelper::map(
            Player::find()
                ->joinWith(['country'])
                ->groupBy(['country.id'])
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
            'text'
        );

        $myApplicationArray = [];
        $myPlayerArray = [];
        if ($this->myTeam) {
            $myApplicationArray = Transfer::find()
                ->where([
                    'ready' => null,
                    'id' => TransferApplication::find()
                        ->select(['transfer_id'])
                        ->where(['team_id' => $this->myTeam->id])
                ])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            $myPlayerArray = Transfer::find()
                ->where(['team_seller_id' => $this->myTeam->id, 'ready' => null])
                ->orderBy(['id' => SORT_ASC])
                ->all();
        }

        $this->setSeoTitle('Трансфер игроков');

        return $this->render('index', [
            'countryArray' => $countryArray,
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
            'myApplicationArray' => $myApplicationArray,
            'myPlayerArray' => $myPlayerArray,
            'positionArray' => $positionArray,
        ]);
    }

    /**
     * @return string
     */
    public function actionHistory()
    {
        $searchModel = new TransferHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $countryArray = ArrayHelper::map(
            Player::find()
                ->joinWith([
                    'country',
                ])
                ->groupBy(['country_id'])
                ->orderBy(['name' => SORT_ASC])
                ->all(),
            'country.id',
            'country.name'
        );

        $positionArray = ArrayHelper::map(
            Position::find()
                ->orderBy(['id' => SORT_ASC])
                ->all(),
            'id',
            'text'
        );

        $this->setSeoTitle('Трансфер игроков');

        return $this->render('history', [
            'countryArray' => $countryArray,
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
            'positionArray' => $positionArray,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        $transfer = Transfer::find()
            ->where(['id' => $id])
            ->andWhere(['not', ['ready' => null]])
            ->limit(1)
            ->one();
        $this->notFound($transfer);

        $model = new TransferVote(['transferId' => $id]);
        if ($model->saveVote()) {
            $this->setSuccessFlash('Ваш голос успешно сохранён');

            /**
             * @var Federation[] $presidentFederationArray
             */
            $presidentFederationArray = Federation::find()
                ->where([
                    'or',
                    ['president_user_id' => $this->user->id],
                    ['vice_user_id' => $this->user->id],
                ])
                ->all();

            if ($presidentFederationArray) {
                $presidentTeamIds = [];
                $presidentCountryIds = [];
                foreach ($presidentFederationArray as $federation) {
                    $presidentCountryIds[] = $federation->country_id;
                    foreach ($federation->country->cities as $city) {
                        foreach ($city->stadiums as $stadium) {
                            $presidentTeamIds[] = $stadium->team->id;
                        }
                    }
                }

                /**
                 * @var Transfer $transfer
                 */
                $transfer = Transfer::find()
                    ->joinWith(['player'])
                    ->where([
                        'not',
                        [
                            'transfer.id' => \common\models\db\TransferVote::find()
                                ->select(['transfer_id'])
                                ->where(['user_id' => $this->user->id])
                        ]
                    ])
                    ->andWhere(['voted' => null])
                    ->andWhere(['not', ['ready' => null]])
                    ->andWhere([
                        'or',
                        ['team_buyer_id' => $presidentTeamIds],
                        ['team_seller_id' => $presidentTeamIds],
                        ['player_id' => $presidentCountryIds],
                    ])
                    ->limit(1)
                    ->one();
                if ($transfer) {
                    return $this->redirect(['transfer/view', 'id' => $transfer->id]);
                }

                /**
                 * @var Loan $loan
                 */
                $loan = Loan::find()
                    ->joinWith(['player'])
                    ->where([
                        'not',
                        [
                            'loan.id' => LoanVote::find()
                                ->select(['loan_id'])
                                ->where(['user_id' => $this->user->id])
                        ]
                    ])
                    ->andWhere(['voted' => null])
                    ->andWhere(['not', ['ready' => null]])
                    ->andWhere([
                        'or',
                        ['team_buyer_id' => $presidentTeamIds],
                        ['team_seller_id' => $presidentTeamIds],
                        ['player_id' => $presidentCountryIds],
                    ])
                    ->limit(1)
                    ->one();
                if ($loan) {
                    return $this->redirect(['loan/view', 'id' => $loan->id]);
                }

                $transfer = Transfer::find()
                    ->where([
                        'not',
                        [
                            'transfer.id' => \common\models\db\TransferVote::find()
                                ->select(['transfer_id'])
                                ->where(['user_id' => $this->user->id])
                        ]
                    ])
                    ->andWhere(['voted' => null])
                    ->andWhere(['not', ['ready' => null]])
                    ->andWhere([
                        'transfer.id' => \common\models\db\TransferVote::find()
                            ->select(['transfer_id'])
                            ->where(['<', 'rating', 0])
                    ])
                    ->limit(1)
                    ->one();
                if ($transfer) {
                    return $this->redirect(['transfer/view', 'id' => $transfer->id]);
                }

                $loan = Loan::find()
                    ->where([
                        'not',
                        [
                            'loan.id' => LoanVote::find()
                                ->select(['loan_id'])
                                ->where(['user_id' => $this->user->id])
                        ]
                    ])
                    ->andWhere(['voted' => null])
                    ->andWhere(['not', ['ready' => null]])
                    ->andWhere([
                        'loan.id' => LoanVote::find()
                            ->select(['loan_id'])
                            ->where(['<', 'rating', 0])
                    ])
                    ->limit(1)
                    ->one();
                if ($loan) {
                    return $this->redirect(['loan/view', 'id' => $loan->id]);
                }
            }
            return $this->refresh();
        }

        $query = TransferApplication::find()
            ->where(['transfer_id' => $id])
            ->orderBy(['price' => SORT_DESC, 'date' => SORT_DESC]);
        $applicationDataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
        ]);

        $query = TransferComment::find()
            ->where(['transfer_id' => $id])
            ->orderBy(['id' => SORT_ASC]);
        $commentDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNewsComment'],
            ],
        ]);

        $this->setSeoTitle('Трансферная сделка');

        return $this->render('view', [
            'applicationDataProvider' => $applicationDataProvider,
            'commentDataProvider' => $commentDataProvider,
            'model' => $model,
            'transfer' => $transfer,
            'userBlockComment' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one() : null,
            'userBlockDeal' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT_DEAL)->one() : null,
        ]);
    }

    /**
     * @param int $id
     * @param int $transferId
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDeleteComment(int $id, int $transferId): Response
    {
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        if (UserRole::ADMIN !== $user->user_role_id) {
            $this->forbiddenRole();
        }

        $model = TransferComment::find()
            ->where(['id' => $id, 'transfer_id' => $transferId])
            ->limit(1)
            ->one();
        $this->notFound($model);

        try {
            $model->delete();
            $this->setSuccessFlash('Комментарий успешно удалён.');
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['view', 'id' => $transferId]);
    }
}
