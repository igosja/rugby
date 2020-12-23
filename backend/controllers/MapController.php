<?php

// TODO refactor

namespace backend\controllers;

/**
 * Class MapController
 * @package frontend\controllers
 */
class MapController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    /**
     * @return string
     */
    public function actionPlan()
    {
        return $this->renderPartial('plan');
    }
}
