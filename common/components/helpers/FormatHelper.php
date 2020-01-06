<?php

namespace common\components\helpers;

use Exception;
use frontend\components\AbstractController;
use Yii;

/**
 * Class FormatHelper
 * @package common\components
 */
class FormatHelper
{
    /**
     * @param int $sum
     * @return string
     */
    public static function asCurrency(int $sum): string
    {
        $result = '';
        try {
            $result = Yii::$app->formatter->asCurrency($sum, 'EUR');
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }
        return $result;
    }

    /**
     * @param int $time
     * @return string
     */
    public static function asDate(int $time): string
    {
        $result = '';
        try {
            /**
             * @var AbstractController $controller
             */
            $controller = Yii::$app->controller;
            if (isset($controller->user, $controller->user->user_timezone) && $controller->user && $controller->user->user_timezone) {
                Yii::$app->formatter->timeZone = $controller->user->user_timezone;
            }

            $result = Yii::$app->formatter->asDate($time, 'short');
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }
        return $result;
    }

    /**
     * @param int $time
     * @return string
     */
    public static function asDateTime(int $time): string
    {
        $result = '';
        try {
            /**
             * @var AbstractController $controller
             */
            $controller = Yii::$app->controller;
            if (isset($controller->user, $controller->user->user_timezone) && $controller->user && $controller->user->user_timezone) {
                Yii::$app->formatter->timeZone = $controller->user->user_timezone;
            }

            $result = Yii::$app->formatter->asDatetime($time, 'short');
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        return $result;
    }
}