<?php

// TODO refactor

namespace console\controllers;

use common\models\db\Test;
use DateTime;
use DateTimeZone;

/**
 * Generator configuration.
 *
 * Class TestController
 * @package console\controllers
 */
class TestController extends AbstractController
{
    /**
     * Generator configuration.
     *
     * @return void
     */
    public function actionIndex(): void
    {
        $formattedTime = (new DateTime('now', new DateTimeZone('UTC')))->format('H:i');
        $model = new Test();
        $model->value = $formattedTime;
        $model->save();
    }
}
