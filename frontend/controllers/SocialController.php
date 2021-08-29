<?php

// TODO refactor

namespace frontend\controllers;

use common\models\db\User;
use Exception;
use frontend\models\forms\OAuthFacebook;
use frontend\models\forms\OAuthGoogle;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class SocialController
 * @package frontend\controllers
 */
class SocialController extends AbstractController
{
    public const FACEBOOK = 'fb';
    public const GOOGLE = 'gl';

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'connect',
                    'disconnect',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'connect',
                            'disconnect',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $id
     * @return Response
     * @throws Exception
     */
    public function actionConnect(string $id): Response
    {
        $oauthId = '';
        $field = '';

        if (self::GOOGLE === $id) {
            $oauthId = OAuthGoogle::getId('connect');
            $field = 'social_google_id';
        } elseif (self::FACEBOOK === $id) {
            $oauthId = OAuthFacebook::getId('connect');
            $field = 'social_facebook_id';
        }

        if (!$oauthId) {
            $this->setErrorFlash();
            return $this->redirect(['user/social']);
        }

        $user = User::find()
            ->select(['user_id'])
            ->where([$field => $oauthId])
            ->andWhere(['!=', 'user_id', $this->user->id])
            ->limit(1)
            ->one();
        if ($user) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.social.connect.error'));
            return $this->redirect(['user/social']);
        }

        $this->user->$field = $oauthId;
        $this->user->save(true, [
            $field,
        ]);

        $this->setSuccessFlash();
        return $this->redirect(['user/social']);
    }

    /**
     * @param string $id
     * @return Response
     * @throws Exception
     */
    public function actionDisconnect(string $id): Response
    {
        $field = '';

        if (self::GOOGLE === $id) {
            $field = 'social_google_id';
        } elseif (self::FACEBOOK === $id) {
            $field = 'social_facebook_id';
        }

        $this->user->$field = '';
        $this->user->save(true, [
            $field,
        ]);

        $this->setSuccessFlash();
        return $this->redirect(['user/social']);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function actionLogin(string $id): Response
    {
        $oauthId = '';
        $field = '';

        if (self::GOOGLE === $id) {
            $oauthId = OAuthGoogle::getId('login');
            $field = 'social_google_id';
        } elseif (self::FACEBOOK === $id) {
            $oauthId = OAuthFacebook::getId('login');
            $field = 'social_facebook_id';
        }

        if (!$oauthId) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.social.login.error'));
            return $this->redirect(['site/login']);
        }

        $user = User::find()
            ->where([$field => $oauthId])
            ->limit(1)
            ->one();
        if (!$user) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.social.connect.error'));
            return $this->redirect(['site/sign-in']);
        }

        Yii::$app->user->login($user, 2592000);
        return $this->redirect(['team/view']);
    }
}
