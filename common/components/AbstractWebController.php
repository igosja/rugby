<?php

namespace common\components;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class AbstractWebController
 * @package common\components
 */
abstract class AbstractWebController extends Controller
{
    /**
     * @param ActiveRecord|null $model
     * @throws NotFoundHttpException
     */
    protected function notFound(ActiveRecord $model = null): void
    {
        if (!$model) {
            throw new NotFoundHttpException('Страница не найдена');
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
