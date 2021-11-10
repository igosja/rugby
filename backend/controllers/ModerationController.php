<?php

// TODO refactor

namespace backend\controllers;

use common\models\db\Chat;
use common\models\db\ForumMessage;
use common\models\db\GameComment;
use common\models\db\LoanComment;
use common\models\db\News;
use common\models\db\NewsComment;
use common\models\db\TransferComment;
use Yii;
use yii\web\Response;

/**
 * Class ModerationController
 * @package backend\controllers
 */
class ModerationController extends AbstractController
{
    /**
     * @return Response
     */
    public function actionIndex(): Response
    {
        return $this->redirect(['moderation/chat']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionChat()
    {
        $model = Chat::find()
            ->where(['check' => null])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$model) {
            return $this->redirect(['forum-message']);
        }

        $this->view->title = 'Chat';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('chat', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \Exception
     */
    public function actionChatUpdate(int $id)
    {
        $model = Chat::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['chat']);
        }

        $this->view->title = 'Chat';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('chat-update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionChatOk(int $id): Response
    {
        Chat::updateAll(['check' => time()], ['id' => $id]);
        return $this->redirect(['chat']);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionChatDelete(int $id): Response
    {
        $model = Chat::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['chat']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionForumMessage()
    {
        $model = ForumMessage::find()
            ->where(['check' => null])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$model) {
            return $this->redirect(['game-comment']);
        }

        $this->view->title = 'Forum';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('forum-message', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \Exception
     */
    public function actionForumMessageUpdate(int $id)
    {
        $model = ForumMessage::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['forum-message']);
        }

        $this->view->title = 'Forum';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('forum-message-update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionForumMessageOk(int $id): Response
    {
        ForumMessage::updateAll(['check' => time()], ['id' => $id]);
        return $this->redirect(['forum-message']);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionForumMessageDelete(int $id): Response
    {
        $model = ForumMessage::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['forum-message']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionGameComment()
    {
        $model = GameComment::find()
            ->where(['check' => null])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$model) {
            return $this->redirect(['news']);
        }

        $this->view->title = 'Game comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('game-comment', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \Exception
     */
    public function actionGameCommentUpdate(int $id)
    {
        $model = GameComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['game-comment']);
        }

        $this->view->title = 'Game comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('game-comment-update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionGameCommentOk(int $id): Response
    {
        GameComment::updateAll(['check' => time()], ['id' => $id]);
        return $this->redirect(['game-comment']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionGameCommentDelete(int $id): Response
    {
        $model = GameComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['game-comment']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionNewsComment()
    {
        $model = NewsComment::find()
            ->where(['check' => null])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$model) {
            return $this->redirect(['loan-comment']);
        }

        $this->view->title = 'News comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('news-comment', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \Exception
     */
    public function actionNewsCommentUpdate(int $id)
    {
        $model = NewsComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['news-comment']);
        }

        $this->view->title = 'News comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('news-comment-update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionNewsCommentOk(int $id): Response
    {
        NewsComment::updateAll(['check' => time()], ['id' => $id]);
        return $this->redirect(['news-comment']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionNewsCommentDelete(int $id): Response
    {
        $model = NewsComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['news-comment']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionNews()
    {
        $model = News::find()
            ->where(['check' => null])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$model) {
            return $this->redirect(['news-comment']);
        }

        $this->view->title = 'News';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('news', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \Exception
     */
    public function actionNewsUpdate(int $id)
    {
        $model = News::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['news']);
        }

        $this->view->title = 'News';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('news-update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionNewsOk(int $id): Response
    {
        News::updateAll(['check' => time()], ['id' => $id]);
        return $this->redirect(['news']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionNewsDelete(int $id): Response
    {
        $model = News::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['news']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionLoanComment()
    {
        $model = LoanComment::find()
            ->where(['check' => null])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$model) {
            return $this->redirect(['moderation/transfer-comment']);
        }

        $this->view->title = 'Loan comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('loan-comment', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \Exception
     */
    public function actionLoanCommentUpdate(int $id)
    {
        $model = LoanComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['loan-comment']);
        }

        $this->view->title = 'Loan comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('loan-comment-update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionLoanCommentOk(int $id): Response
    {
        LoanComment::updateAll(['check' => time()], ['id' => $id]);
        return $this->redirect(['loan-comment']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionLoanCommentDelete(int $id): Response
    {
        $model = LoanComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['loan-comment']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionTransferComment()
    {
        $model = TransferComment::find()
            ->where(['check' => null])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();

        if (!$model) {
            return $this->redirect(['site/index']);
        }

        $this->view->title = 'Transfer comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('transfer-comment', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws \Exception
     */
    public function actionTransferCommentUpdate(int $id)
    {
        $model = TransferComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->setSuccessFlash();
            return $this->redirect(['transfer-comment']);
        }

        $this->view->title = 'Transfer comment';
        $this->view->params['breadcrumbs'][] = ['label' => 'Moderation', 'url' => ['moderation/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('transfer-comment-update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionTransferCommentOk(int $id): Response
    {
        TransferComment::updateAll(['check' => time()], ['id' => $id]);
        return $this->redirect(['moderation/transfer-comment']);
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionTransferCommentDelete(int $id): Response
    {
        $model = TransferComment::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        if ($model) {
            $model->delete();
        }
        return $this->redirect(['transfer-comment']);
    }
}
