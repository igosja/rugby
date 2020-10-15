<?php

namespace backend\controllers;

use backend\components\AbstractController;
use backend\models\forms\SignInForm;
use common\models\db\Complaint;
use common\models\db\ForumMessage;
use common\models\db\GameComment;
use common\models\db\LoanComment;
use common\models\db\Logo;
use common\models\db\News;
use common\models\db\NewsComment;
use common\models\db\Payment;
use common\models\db\Support;
use common\models\db\Team;
use common\models\db\TransferComment;
use common\models\db\Vote;
use common\models\db\VoteStatus;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class SiteController
 * @package backend\controllers
 */
class SiteController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['sign-out', 'index'],
                'rules' => [
                    [
                        'actions' => ['sign-out', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $chat = 0;
        $complaint = Complaint::find()->where(['complaint_ready' => 0])->count();
        $forumMessage = ForumMessage::find()->where(['forum_message_check' => 0])->count();
        $freeTeam = Team::find()->where(['team_user_id' => 0])->andWhere(['!=', 'team_id', 0])->count();
        $gameComment = GameComment::find()->where(['game_comment_check' => 0])->count();
        $loanComment = LoanComment::find()->where(['loan_comment_check' => 0])->count();
        $logo = Logo::find()->where(['!=', 'logo_team_id', 0])->count();
        $photo = Logo::find()->where(['logo_team_id' => 0])->count();
        $news = News::find()->where(['news_check' => 0])->count();
        $newsComment = NewsComment::find()->where(['news_comment_check' => 0])->count();
        $support = Support::find()->where(['support_question' => 1, 'support_read' => 0, 'support_inside' => 0])->count();
        $transferComment = TransferComment::find()->where(['transfer_comment_check' => 0])->count();
        $poll = Vote::find()->where(['poll_poll_status_id' => VoteStatus::NEW_ONE])->count();

        $countModeration = 0;
        $countModeration = $countModeration + $forumMessage;
        $countModeration = $countModeration + $gameComment;
        $countModeration = $countModeration + $loanComment;
        $countModeration = $countModeration + $news;
        $countModeration = $countModeration + $newsComment;
        $countModeration = $countModeration + $transferComment;
        $countModeration = $countModeration + $chat;

        list($paymentCategories, $paymentData) = Payment::getPaymentHighChartsData();

        $paymentArray = Payment::find()
            ->with([
                'user',
            ])
            ->where(['payment_status' => Payment::PAID])
            ->limit(10)
            ->orderBy(['payment_id' => SORT_DESC])
            ->all();

        $this->view->title = 'Admin';
        return $this->render('index', [
            'chat' => $chat,
            'complaint' => $complaint,
            'countModeration' => $countModeration,
            'forumMessage' => $forumMessage,
            'freeTeam' => $freeTeam,
            'gameComment' => $gameComment,
            'loanComment' => $loanComment,
            'logo' => $logo,
            'news' => $news,
            'newsComment' => $newsComment,
            'paymentArray' => $paymentArray,
            'paymentCategories' => $paymentCategories,
            'paymentData' => $paymentData,
            'photo' => $photo,
            'poll' => $poll,
            'support' => $support,
            'transferComment' => $transferComment,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionSignIn()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new SignInForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $this->layout = 'sign-in';
        $this->view->title = 'Sign In';
        return $this->render('sign-in', [
            'model' => $model,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->redirect(['site/index']);
    }
}
