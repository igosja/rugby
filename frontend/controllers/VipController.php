<?php

// TODO refactor

namespace frontend\controllers;
use frontend\models\queries\UserQuery;
use Yii;

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

        $this->setSeoTitle(Yii::t('frontend', 'controllers.vip.index.title'));
        return $this->render('index', [
            'count' => $count,
        ]);
    }
}
