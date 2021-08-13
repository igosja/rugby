<?php

// TODO refactor

namespace common\components\helpers;

use common\components\AbstractWebController;
use Exception;
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
             * @var AbstractWebController $controller
             */
            $controller = Yii::$app->controller;
            if ($controller->user && $controller->user->timezone) {
                Yii::$app->formatter->timeZone = $controller->user->timezone;
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
             * @var AbstractWebController $controller
             */
            $controller = Yii::$app->controller;
            if ($controller->user && $controller->user->timezone) {
                Yii::$app->formatter->timeZone = $controller->user->timezone;
            }

            $result = Yii::$app->formatter->asDatetime($time, 'short');
        } catch (Exception $e) {
            ErrorHelper::log($e);
        }

        return $result;
    }
}