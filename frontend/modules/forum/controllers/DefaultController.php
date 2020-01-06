<?php

namespace frontend\modules\forum\controllers;

use frontend\components\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
