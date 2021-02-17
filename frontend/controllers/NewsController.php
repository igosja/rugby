<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\NewsComment;
use common\models\db\UserRole;
use frontend\models\executors\NewsCommentSaveExecutor;
use frontend\models\preparers\NewsCommentPrepare;
use frontend\models\preparers\NewsPrepare;
use frontend\models\queries\NewsCommentQuery;
use frontend\models\queries\NewsQuery;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class NewsController
 * @package frontend\controllers
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
                'only' => ['delete-comment'],
                'rules' => [
                    [
                        'actions' => ['delete-comment'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = NewsPrepare::getNewsDataProvider();
        if ($this->user) {
            NewsQuery::updateUserNewsId($this->user);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.news.index.title'));
        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        $news = NewsQuery::getNewsById($id);
        $this->notFound($news);

        $model = new NewsComment();
        $model->news_id = $id;
        $model->user_id = $this->user->id;
        if ($this->user && (new NewsCommentSaveExecutor($this->user, $model, Yii::$app->request->post()))->execute()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.news.view.success'));
            return $this->refresh();
        }

        $dataProvider = NewsCommentPrepare::getNewsCommentDataProvider($id);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.news.view.title'));
        return $this->render(
            'view',
            [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'news' => $news,
            ]
        );
    }

    /**
     * @param int $id
     * @param int $newsId
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDeleteComment(int $id, int $newsId): Response
    {
        if (UserRole::ADMIN !== $this->user->user_role_id) {
            $this->forbiddenRole();
        }

        /**
         * @var NewsComment $model
         */
        $model = NewsCommentQuery::getNewsCommentById($id, $newsId);
        $this->notFound($model);

        try {
            $model->delete();
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.news.delete-comment.success'));
        } catch (Throwable $e) {
            ErrorHelper::log($e);
        }

        return $this->redirect(['view', 'id' => $newsId]);
    }
}
