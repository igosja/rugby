<?php

// TODO refactor

namespace backend\controllers;

use backend\models\forms\SignInForm;
use backend\models\preparers\PaymentPrepare;
use backend\models\queries\ChatQuery;
use backend\models\queries\ForumMessageQuery;
use backend\models\queries\GameCommentQuery;
use backend\models\queries\LoanCommentQuery;
use backend\models\queries\NewsCommentQuery;
use backend\models\queries\NewsQuery;
use backend\models\queries\TransferCommentQuery;
use common\models\db\Site;
use console\models\generator\SiteOpen;
use rmrevin\yii\fontawesome\FAS;
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
                'only' => ['sign-out', 'index', 'status'],
                'rules' => [
                    [
                        'actions' => ['sign-out', 'index', 'status'],
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

    public function actionGen()
    {
        $model = new SiteOpen;
        $model->execute();
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $chat = ChatQuery::countUnchecked();
        $forumMessage = ForumMessageQuery::countUnchecked();
        $gameComment = GameCommentQuery::countUnchecked();
        $loanComment = LoanCommentQuery::countUnchecked();
        $news = NewsQuery::countUnchecked();
        $newsComment = NewsCommentQuery::countUnchecked();
        $transferComment = TransferCommentQuery::countUnchecked();

        $countModeration = 0;
        $countModeration += $forumMessage;
        $countModeration += $gameComment;
        $countModeration += $loanComment;
        $countModeration += $news;
        $countModeration += $newsComment;
        $countModeration += $transferComment;
        $countModeration += $chat;

        [$paymentCategories, $paymentData] = PaymentPrepare::getPaymentHighChartsData();
        $paymentDataProvider = PaymentPrepare::getIndexDataProvider();

        $panels = [
            [
                'class' => 'freeTeam',
                'color' => 'green',
                'icon' => FAS::_FOOTBALL_BALL,
                'text' => 'Free teams',
                'url' => ['team/index'],
            ],
            [
                'class' => 'logo',
                'color' => 'primary',
                'icon' => FAS::_SHIELD_ALT,
                'text' => 'Logos',
                'url' => ['logo/index'],
            ],
            [
                'class' => 'photo',
                'color' => 'primary',
                'icon' => FAS::_USER,
                'text' => 'Photos',
                'url' => ['photo/index'],
            ],
            [
                'class' => 'support',
                'color' => 'red',
                'icon' => FAS::_COMMENTS,
                'text' => 'Support',
                'url' => ['support/index'],
            ],
            [
                'class' => 'complaint',
                'color' => 'red',
                'icon' => FAS::_EXCLAMATION_CIRCLE,
                'text' => 'Complaints',
                'url' => ['complaint/index'],
            ],
            [
                'class' => 'vote',
                'color' => 'yellow',
                'icon' => FAS::_CHART_BAR,
                'text' => 'Voting',
                'url' => ['vote/index'],
            ],
        ];
        $moderation = [
            ['text' => 'Chat', 'value' => $chat, 'url' => ['moderation/chat']],
            ['text' => 'Forum', 'value' => $forumMessage, 'url' => ['moderation/forum-message']],
            ['text' => 'Game comments', 'value' => $gameComment, 'url' => ['moderation/game-comment']],
            ['text' => 'News', 'value' => $news, 'url' => ['moderation/news']],
            ['text' => 'News comments', 'value' => $newsComment, 'url' => ['moderation/news-comment']],
            ['text' => 'Loan comments', 'value' => $loanComment, 'url' => ['moderation/loan-comment']],
            ['text' => 'Transfer comments', 'value' => $transferComment, 'url' => ['moderation/transfer-comment']],
        ];

        $this->view->title = 'Admin panel';
        return $this->render(
            'index',
            [
                'countModeration' => $countModeration,
                'moderation' => $moderation,
                'panels' => $panels,
                'paymentCategories' => $paymentCategories,
                'paymentData' => $paymentData,
                'paymentDataProvider' => $paymentDataProvider,
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
    public function actionStatus(): Response
    {
        Site::switchStatus();

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->redirect(['site/index']);
    }

    /**
     * @param int|null $id
     * @return string|Response
     */
    public function actionVersion(int $id = null)
    {
        $site = Site::find()
            ->where(['id' => 1])
            ->limit(1)
            ->one();
        if ($id) {
            if (1 === $id) {
                $site->version_1++;
                $site->version_2 = 0;
                $site->version_3 = 0;
            } elseif (2 === $id) {
                $site->version_2++;
                $site->version_3 = 0;
            } else {
                $site->version_3++;
            }

            $site->version_date = time();
            $site->save();

            $this->setSuccessFlash();
            return $this->redirect(['site/version']);
        }

        $this->view->title = 'Site version';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('version', [
            'site' => $site,
        ]);
    }
}
