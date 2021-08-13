<?php

// TODO refactor

namespace common\components;

use common\models\db\User;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class AbstractWebController
 * @package common\components
 *
 * @property User $user
 * @property-write string $successFlash
 * @property-write string $errorFlash
 */
abstract class AbstractWebController extends Controller
{
    /**
     * @var User|null $user
     */
    public ?User $user = null;

    /**
     * @param Action $action
     * @return bool|Response
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->user->isGuest) {
            $this->loadCurrentUser();
        }

        return true;
    }

    private function loadCurrentUser(): void
    {
        $this->user = Yii::$app->user->identity;
    }

    /**
     * @param ActiveRecord|null $model
     * @throws NotFoundHttpException
     */
    protected function notFound(ActiveRecord $model = null): void
    {
        if (!$model) {
            throw new NotFoundHttpException('Page not found');
        }
    }

    /**
     * @param string $text
     */
    protected function setErrorFlash(string $text = 'Error'): void
    {
        Yii::$app->session->setFlash('error', $text);
    }

    /**
     * @param string $text
     */
    protected function setSuccessFlash(string $text = 'Success'): void
    {
        Yii::$app->session->setFlash('success', $text);
    }
}
