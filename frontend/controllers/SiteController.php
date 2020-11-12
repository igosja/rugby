<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\User;
use Exception;
use frontend\models\forms\ActivationForm;
use frontend\models\forms\ActivationRepeatForm;
use frontend\models\forms\ForgotPasswordForm;
use frontend\models\forms\PasswordRestoreForm;
use frontend\models\forms\SignInForm;
use frontend\models\forms\SignUpForm;
use frontend\models\queries\ForumMessageQuery;
use frontend\models\queries\NewsQuery;
use frontend\models\queries\UserQuery;
use Yii;
use yii\filters\AccessControl;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

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
     * @return array|string
     */
    public function actionActivation()
    {
        $activationForm = new ActivationForm();

        if (Yii::$app->request->isAjax && $activationForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($activationForm);
        }

        if (($activationForm->load(Yii::$app->request->post()) ||
                $activationForm->load(Yii::$app->request->get(), '')) && $activationForm->code) {
            try {
                if ($activationForm->activate()) {
                    $this->setSuccessFlash('Активация прошла успешно');
                    return $this->redirect(['site/activation']);
                }
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            $this->setErrorFlash('Не удалось провести активацию');
        }

        $this->setSeoTitle('Активация аккаунта');

        return $this->render(
            'activation',
            [
                'activationForm' => $activationForm,
            ]
        );
    }

    /**
     * @return array|string
     */
    public function actionActivationRepeat()
    {
        $activationRepeatForm = new ActivationRepeatForm();

        if (Yii::$app->request->isAjax && $activationRepeatForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($activationRepeatForm);
        }

        if ($activationRepeatForm->load(Yii::$app->request->post())) {
            try {
                if ($activationRepeatForm->send()) {
                    $this->setSuccessFlash('Код активации успешно отправлен');
                    return $this->redirect(['site/activation']);
                }
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            $this->setErrorFlash('Не удалось отправить код активации');
        }

        $this->setSeoTitle('Активация аккаунта');
        return $this->render(
            'activation-repeat',
            [
                'activationRepeatForm' => $activationRepeatForm,
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
     * @return array|string|Response
     */
    public function actionForgotPassword()
    {
        $forgotPasswordForm = new ForgotPasswordForm();

        if (Yii::$app->request->isAjax && $forgotPasswordForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($forgotPasswordForm);
        }

        if ($forgotPasswordForm->load(Yii::$app->request->post())) {
            try {
                if ($forgotPasswordForm->send()) {
                    $this->setSuccessFlash(
                        'Письмо с инструкциями по восстановлению пароля успешно отправлено на email'
                    );
                    return $this->refresh();
                }

                $this->setErrorFlash('Не удалось восстановить пароль');
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash('Не удалось восстановить пароль');
            }
        }

        $this->setSeoTitle('Восстановление пароля');
        return $this->render(
            'forgot-password',
            [
                'forgotPasswordForm' => $forgotPasswordForm,
            ]
        );
    }

    /**
     * @return array|string|Response
     */
    public function actionPasswordRestore()
    {
        $passwordRestoreForm = new PasswordRestoreForm();
        $passwordRestoreForm->setAttributes(Yii::$app->request->get());

        if (Yii::$app->request->isAjax && $passwordRestoreForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($passwordRestoreForm);
        }

        if ($passwordRestoreForm->load(Yii::$app->request->post())) {
            try {
                if ($passwordRestoreForm->restore()) {
                    $this->setSuccessFlash('Пароль успешно изменён');
                    return $this->redirect(['sign-in']);
                }

                $this->setErrorFlash('Не удалось изменить пароль');
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash('Не удалось изменить пароль');
            }
        }

        $this->setSeoTitle('Восстановление пароля');
        return $this->render(
            'password-restore',
            [
                'passwordRestoreForm' => $passwordRestoreForm,
            ]
        );
    }

    /**
     * @return array|string|Response
     */
    public function actionSignIn()
    {
        $signInForm = new SignInForm();

        if (Yii::$app->request->isAjax && $signInForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($signInForm);
        }

        if ($signInForm->load(Yii::$app->request->post()) && $signInForm->login()) {
            return $this->redirect(['team/view']);
        }

        $this->setSeoTitle('Вход');
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
     * @return array|string|Response
     */
    public function actionSignUp()
    {
        $model = new SignUpForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->signUp()) {
                    $this->setSuccessFlash('Регистрация прошла успешно. Осталось подтвердить ваш email.');
                    return $this->redirect(['site/activation']);
                }
                $this->setErrorFlash('Не удалось провести регистрацию');
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash('Не удалось провести регистрацию');
            }
        }

        $this->setSeoTitle('Регистрация');
        return $this->render(
            'sign-up',
            [
                'model' => $model,
            ]
        );
    }
}
