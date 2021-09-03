<?php

// TODO refactor

namespace backend\controllers;

use common\models\db\Test;
use yii\data\ActiveDataProvider;

/**
 * Class TestController
 * @package backend\controllers
 */
class TestController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'Test';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider(
                [
                    'query' => Test::find(),
                    'sort' => [
                        'defaultOrder' => ['id' => SORT_DESC],
                    ],
                ]
            ),
        ]);
    }
}
