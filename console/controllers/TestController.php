<?php

// TODO refactor

namespace console\controllers;

use DateTime;
use DateTimeZone;

/**
 * Class TestController
 * @package console\controllers
 */
class TestController extends AbstractController
{
    /**
     * @throws \Exception
     */
    public function actionIndex(): void
    {
        $this->stdout((new DateTime('now', new DateTimeZone('UTC')))->format('H:i'));
    }
}
