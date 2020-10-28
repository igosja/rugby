<?php

namespace frontend\controllers;

use common\models\db\User;
use frontend\models\forms\SignInForm;
use frontend\models\queries\ForumMessageQuery;
use frontend\models\queries\NewsQuery;
use frontend\models\queries\UserQuery;
use Yii;
use yii\filters\AccessControl;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SiteController
 * @package frontend\controllers
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
                'only' => ['sign-out', 'sign-up', 'sign-in', 'forgot-password'],
                'rules' => [
                    [
                        'actions' => ['sign-up', 'sign-in', 'forgot-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['sign-out'],
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
        $birthdayBoys = UserQuery::getBirthdayBoys();
        $forumMessage = ForumMessageQuery::getLastForumGroupsByMessageDate();
        $news = NewsQuery::getLastNews();

        $this->view->title = 'Регбийный онлайн менеджер';
        $this->view->registerMetaTag(
            [
                'name' => 'description',
                'content' => 'Виртуальная Регбийная Лига - лучший бесплатный регбийный онлайн-менеджер',
            ]
        );

        return $this->render(
            'index',
            [
                'birthdayBoys' => $birthdayBoys,
                'forumMessage' => $forumMessage,
                'news' => $news,
            ]
        );
    }

    /**
     * @param string $code
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAuth(string $code): Response
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        $user = User::find()
            ->where(['user_code' => $code])
            ->limit(1)
            ->one();
        $this->notFound($user);

        Yii::$app->user->login($user, 2592000);

        return $this->redirect(['team/view']);
    }

    /**
     * @return string
     */
    public function actionClosed(): string
    {
        return $this->render('closed');
    }

    /**
     *
     */
    public function actionForgotPassword(): void
    {
    }

    /**
     * @return string|Response
     */
    public function actionSignIn()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['team/view']);
        }

        $signInForm = new SignInForm();
        if ($signInForm->load(Yii::$app->request->post()) && $signInForm->login()) {
            return $this->redirect(['team/view']);
        }

        $this->seoTitle('Вход');
        return $this->render(
            'sign-in',
            [
                'signInForm' => $signInForm,
            ]
        );
    }

    /**
     * @return Response
     */
    public function actionSignOut(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     *
     */
    public function actionSignUp(): void
    {
    }

    /**
     *
     */
    public function actionRequestPasswordReset(): void
    {
    }

    /**
     * @param string $token
     */
    public function actionResetPassword(string $token): void
    {
    }

    /**
     * @param string $token
     */
    public function actionVerifyEmail(string $token): void
    {
    }

    /**
     *
     */
    public function actionResendVerificationEmail(): void
    {
    }
}
