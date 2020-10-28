<?php

namespace backend\controllers;

use backend\models\forms\SignInForm;
use backend\models\preparers\PaymentPrepare;
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
use yii\web\ErrorAction;
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
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $chat = 0;
        $complaint = Complaint::find()->andWhere(['ready' => 0])->count();
        $forumMessage = ForumMessage::find()->andWhere(['check' => 0])->count();
        $freeTeam = Team::find()->andWhere(['user_id' => 0])->andWhere(['!=', 'id', 0])->count();
        $gameComment = GameComment::find()->andWhere(['check' => 0])->count();
        $loanComment = LoanComment::find()->andWhere(['check' => 0])->count();
        $logo = Logo::find()->andWhere(['not', ['team_id' => null]])->count();
        $photo = Logo::find()->andWhere(['team_id' => null])->count();
        $news = News::find()->andWhere(['check' => 0])->count();
        $newsComment = NewsComment::find()->andWhere(['check' => 0])->count();
        $support = Support::find()->andWhere(['is_question' => true, 'read' => 0, 'is_inside' => false])->count();
        $transferComment = TransferComment::find()->andWhere(['check' => 0])->count();
        $poll = Vote::find()->andWhere(['vote_status_id' => VoteStatus::NEW_ONE])->count();

        $countModeration = 0;
        $countModeration += $forumMessage;
        $countModeration += $gameComment;
        $countModeration += $loanComment;
        $countModeration += $news;
        $countModeration += $newsComment;
        $countModeration += $transferComment;
        $countModeration += $chat;

        [$paymentCategories, $paymentData] = PaymentPrepare::getPaymentHighChartsData();

        $paymentArray = Payment::find()
            ->with(
                [
                    'user',
                ]
            )
            ->andWhere(['status' => Payment::PAID])
            ->limit(10)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $this->view->title = 'Admin';
        return $this->render(
            'index',
            [
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
            ]
        );
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
        return $this->render(
            'sign-in',
            [
                'model' => $model,
            ]
        );
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
