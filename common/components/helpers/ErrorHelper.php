<?php

// TODO refactor

namespace common\components\helpers;

use common\models\db\User;
use console\components\AbstractController;
use Throwable;
use Yii;
use yii\base\Model;

/**
 * Class ErrorHelper
 * @package common\components
 */
class ErrorHelper
{
    /**
     * @param Throwable $e
     */
    public static function log(Throwable $e)
    {
        if (Yii::$app->controller instanceof AbstractController || User::ADMIN_USER_ID == Yii::$app->user->id) {
            print '<pre>';
            print_r($e->__toString());
            exit;
        }

        Yii::error($e->__toString());
    }

    /**
     * @param Model $model
     * @return string
     */
    public static function modelErrorsToString(Model $model): string
    {
        return implode(', ', $model->getErrorSummary(true));
    }
}
