<?php

namespace frontend\controllers;
use frontend\models\queries\UserQuery;

/**
 * Class VipController
 * @package frontend\controllers
 */
class VipController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $count = UserQuery::countVipUsers();

        $this->setSeoTitle('VIP клуб');
        return $this->render('index', [
            'count' => $count,
        ]);
    }
}
