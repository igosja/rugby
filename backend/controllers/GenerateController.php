<?php

// TODO refactor

namespace backend\controllers;

use Yii;

/**
 * Class GenerateController
 * @package backend\controllers
 */
class GenerateController extends AbstractController
{
    /**
     * @return string
     * @throws \Exception
     */
    public function actionPassword(): string
    {
        $chars = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

        if (Yii::$app->request->get('s')) {
            $chars = '!";%:?*()_+=-~/\,.[]{}' . $chars;
        }

        $password = '';
        for ($i = 1; $i <= Yii::$app->request->get('l', 10); $i++) {
            $r = random_int(0, strlen($chars) - 1);
            $password .= $chars[$r];
        }

        $this->view->title = 'Generate password';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('password', [
            'password' => $password,
        ]);
    }
}
