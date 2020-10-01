<?php

namespace frontend\controllers;

use common\models\db\ForumMessage;
use common\models\db\News;
use common\models\db\User;
use frontend\components\AbstractController;
use frontend\models\forms\SignInForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\ErrorAction;
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
        $birthdays = User::find()
            ->where(['user_birth_day' => date('d'), 'user_birth_month' => date('m')])
            ->orderBy(['user_id' => SORT_ASC])
            ->all();
        $countryNews = News::find()
            ->where(['!=', 'news_country_id', 0])
            ->orderBy(['news_id' => SORT_DESC])
            ->limit(10)
            ->one();
        $forumMessage = ForumMessage::find()
            ->select([
                '*',
                'forum_message_id' => 'MAX(forum_message_id)',
                'forum_message_date' => 'MAX(forum_message_date)',
            ])
            ->joinWith(['forumTheme.forumGroup'])
            ->where([
                'forum_group.forum_group_country_id' => 0
            ])
            ->groupBy(['forum_message_forum_theme_id'])
            ->orderBy(['forum_message_id' => SORT_DESC])
            ->limit(10)
            ->all();
        $news = News::find()->where(['news_country_id' => 0])->orderBy(['news_id' => SORT_DESC])->one();

        $this->view->title = 'Регбийный онлайн менеджер';
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Виртуальная Регбийная Лига - лучший бесплатный регбийный онлайн-менеджер',
        ]);

        return $this->render('index', [
            'birthdays' => $birthdays,
            'countryNews' => $countryNews,
            'forumMessage' => $forumMessage,
            'news' => $news,
        ]);
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
    public function actionForgotPassword()
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
        return $this->render('sign-in', [
            'signInForm' => $signInForm,
        ]);
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
    public function actionSignUp()
    {

    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('index');
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('index');
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('index');
    }
}
