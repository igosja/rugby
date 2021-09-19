<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Country;
use common\models\db\News;
use common\models\db\NewsComment;
use common\models\db\UserRole;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class NewsController
 * @package frontend\modules\federation\controllers
 */
class NewsController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'create',
                    'update',
                    'delete',
                    'delete-comment',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'update',
                            'delete',
                            'delete-comment',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id = 0)
    {
        if (!$id) {
            $id = Country::DEFAULT_ID;
            if ($this->myTeamOrVice) {
                $id = $this->myTeamOrVice->stadium->city->country->federation->id;
            }
            return $this->redirect(['index', 'id' => $id]);
        }

        $federation = $this->getFederation($id);

        $query = News::find()
            ->where(['federation_id' => $id])
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNews'],
            ],
        ]);

        if ($this->myTeam && $this->myTeam->stadium->city->country->federation->id === $id) {
            $lastNewsId = News::find()
                ->select(['id'])
                ->where(['federation_id' => $id])
                ->orderBy(['id' => SORT_DESC])
                ->scalar();
            if ($lastNewsId) {
                $this->myTeam->federation_news_id = $lastNewsId;
                $this->myTeam->save(true, ['federation_news_id']);
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news.title'));
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionCreate(int $id)
    {
        $federation = $this->getFederation($id);
        if (!in_array($this->user->id, [$federation->president_user_id, $federation->vice_user_id], true)) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.news-create.error'));
            return $this->redirect(['index', 'id' => $id]);
        }

        $model = new News();
        $model->federation_id = $id;
        $model->user_id = $this->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-create.success'));
            return $this->redirect(['index', 'id' => $id]);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news-create.title'));

        return $this->render('create', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $newsId
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id, int $newsId)
    {
        $federation = $this->getFederation($id);

        $model = News::find()
            ->where(['id' => $newsId, 'federation_id' => $id, 'user_id' => $this->user->id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-update.success'));
            return $this->redirect(['index', 'id' => $id]);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news-update.title'));
        return $this->render('update', [
            'federation' => $federation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $newsId
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id, int $newsId): Response
    {
        $model = News::find()
            ->where(['id' => $newsId, 'federation_id' => $id, 'user_id' => $this->user->id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $model->delete();

        $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-delete.title'));
        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * @param int $id
     * @param int $newsId
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView(int $id, int $newsId)
    {
        $federation = $this->getFederation($id);

        $news = News::find()->where(['id' => $newsId, 'federation_id' => $id])->limit(1)->one();
        $this->notFound($news);

        $model = new NewsComment();
        $model->news_id = $newsId;
        $model->user_id = $this->user->id;
        if ($model->addComment()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.news-view.success'));
            return $this->refresh();
        }

        $query = NewsComment::find()
            ->where(['news_id' => $newsId])
            ->orderBy(['id' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSizeNewsComment'],
            ],
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.news-view.title'));
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
            'model' => $model,
            'news' => $news,
        ]);
    }

    /**
     * @param $id
     * @param $newsId
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDeleteComment($id, $newsId)
    {
        if (UserRole::ADMIN !== $this->user->user_role_id) {
            $this->forbiddenRole();
        }

        /**
         * @var NewsComment $model
         */
        $model = NewsComment::find()
            ->where(['id' => $id, 'news_id' => $newsId])
            ->limit(1)
            ->one();
        $this->notFound($model);

        $news = $model->news;

        try {
            $model->delete();
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.delete-news-comment.success'));
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['view', 'id' => $news->federation_id, 'newsId' => $newsId]);
    }
}
