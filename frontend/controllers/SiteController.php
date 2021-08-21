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

        $this->view->title = Yii::t('frontend', 'controllers.site.index.title');
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => Yii::t('frontend', 'controllers.site.index.description'),
        ]);

        return $this->render('index', [
            'birthdayBoys' => $birthdayBoys,
            'forumMessage' => $forumMessage,
            'news' => $news,
        ]);
    }

    /**
     * @return array|string|\yii\web\Response
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
                    $this->setSuccessFlash(Yii::t('frontend', 'controllers.site.activation.success'));
                    return $this->redirect(['site/activation']);
                }
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            $this->setErrorFlash(Yii::t('frontend', 'controllers.site.activation.error'));
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.site.activation.title'));

        return $this->render('activation', [
            'activationForm' => $activationForm,
        ]);
    }

    /**
     * @return array|string|\yii\web\Response
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
                    $this->setSuccessFlash(Yii::t('frontend', 'controllers.site.activation-repeat.success'));
                    return $this->redirect(['site/activation']);
                }
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            $this->setErrorFlash(Yii::t('frontend', 'controllers.site.activation-repeat.error'));
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.site.activation-repeat.title'));
        return $this->render('activation-repeat', [
            'activationRepeatForm' => $activationRepeatForm,
        ]);
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
                    $this->setSuccessFlash(Yii::t('frontend', 'controllers.site.forgot-password.success'));
                    return $this->refresh();
                }

                $this->setErrorFlash(Yii::t('frontend', 'controllers.site.forgot-password.error'));
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash(Yii::t('frontend', 'controllers.site.forgot-password.error'));
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.site.forgot-password.title'));
        return $this->render('forgot-password', [
            'forgotPasswordForm' => $forgotPasswordForm,
        ]);
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
                    $this->setSuccessFlash(Yii::t('frontend', 'controllers.site.password-restore.success'));
                    return $this->redirect(['sign-in']);
                }

                $this->setErrorFlash(Yii::t('frontend', 'controllers.site.password-restore.error'));
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash(Yii::t('frontend', 'controllers.site.password-restore.error'));
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.site.password-restore.title'));
        return $this->render('password-restore', [
            'passwordRestoreForm' => $passwordRestoreForm,
        ]);
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

        $this->setSeoTitle(Yii::t('frontend', 'controllers.site.sign-in.title'));
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
                    $this->setSuccessFlash(Yii::t('frontend', 'controllers.site.sign-up.success'));
                    return $this->redirect(['site/activation']);
                }
                $this->setErrorFlash(Yii::t('frontend', 'controllers.site.sign-up.error'));
            } catch (Exception $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash(Yii::t('frontend', 'controllers.site.sign-up.error'));
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.site.sign-up.title'));
        return $this->render('sign-up', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRedirect(): Response
    {
        return $this->redirect(['index']);
    }
}
