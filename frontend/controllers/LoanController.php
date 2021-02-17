<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Federation;
use common\models\db\Loan;
use common\models\db\LoanApplication;
use common\models\db\LoanComment;
use common\models\db\Player;
use common\models\db\Position;
use common\models\db\Transfer;
use common\models\db\TransferVote;
use common\models\db\User;
use common\models\db\UserBlockType;
use common\models\db\UserRole;
use frontend\models\forms\LoanVote;
use frontend\models\search\LoanHistorySearch;
use frontend\models\search\LoanSearch;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class LoanController
 * @package frontend\controllers
 */
class LoanController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new LoanSearch();
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

        $myApplicationArray = [];
        $myPlayerArray = [];
        if ($this->myTeam) {
            $myApplicationArray = Loan::find()
                ->where([
                    'ready' => null,
                    'id' => LoanApplication::find()
                        ->select(['loan_id'])
                        ->where(['team_id' => $this->myTeam->id])
                ])
                ->orderBy(['id' => SORT_ASC])
                ->all();
            $myPlayerArray = Loan::find()
                ->where(['team_seller_id' => $this->myTeam->id, 'ready' => null])
                ->orderBy(['id' => SORT_ASC])
                ->all();
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.loan.index.title'));

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
    public function actionHistory(): string
    {
        $searchModel = new LoanHistorySearch();
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

        $this->setSeoTitle(Yii::t('frontend', 'controllers.loan.history.title'));

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
        $loan = Loan::find()
            ->where(['id' => $id])
            ->andWhere(['not', ['ready' => 0]])
            ->limit(1)
            ->one();
        $this->notFound($loan);

        $model = new LoanVote(['loanId' => $id]);
        if ($model->saveVote()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.loan.view.success'));

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
                            'transfer.id' => TransferVote::find()
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
                            'loan.id' => \common\models\db\LoanVote::find()
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
                            'transfer.id' => TransferVote::find()
                                ->select(['transfer_id'])
                                ->where(['user_id' => $this->user->id])
                        ]
                    ])
                    ->andWhere(['voted' => null])
                    ->andWhere(['not', ['ready' => null]])
                    ->andWhere([
                        'transfer.id' => TransferVote::find()
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
                            'loan.id' => \common\models\db\LoanVote::find()
                                ->select(['loan_id'])
                                ->where(['user_id' => $this->user->id])
                        ]
                    ])
                    ->andWhere(['voted' => null])
                    ->andWhere(['not', ['ready' => null]])
                    ->andWhere([
                        'loan.id' => \common\models\db\LoanVote::find()
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

        $query = LoanApplication::find()
            ->where(['loan_id' => $id])
            ->orderBy(['price' => SORT_DESC, 'date' => SORT_DESC]);
        $applicationDataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeTable'],
            ],
            'query' => $query,
        ]);

        $query = LoanComment::find()
            ->with([
                'user',
            ])
            ->where(['loan_id' => $id])
            ->orderBy(['id' => SORT_ASC]);
        $commentDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNewsComment'],
            ],
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.loan.view.title'));

        return $this->render('view', [
            'applicationDataProvider' => $applicationDataProvider,
            'commentDataProvider' => $commentDataProvider,
            'loan' => $loan,
            'model' => $model,
            'userBlockComment' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT)->one() : null,
            'userBlockDeal' => $this->user ? $this->user->getUserBlock(UserBlockType::TYPE_COMMENT_DEAL)->one() : null,
        ]);
    }

    /**
     * @param int $id
     * @param int $loanId
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDeleteComment(int $id, int $loanId): Response
    {
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        if (UserRole::ADMIN !== $user->user_role_id) {
            $this->forbiddenRole();
        }

        $model = LoanComment::find()
            ->where(['id' => $id, 'loan_id' => $loanId])
            ->limit(1)
            ->one();
        $this->notFound($model);

        try {
            $model->delete();
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.loan.delete-comment.success'));
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['loan/view', 'id' => $loanId]);
    }

}
