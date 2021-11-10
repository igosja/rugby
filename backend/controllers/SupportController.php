<?php

// TODO refactor

namespace backend\controllers;

use backend\models\search\SupportSearch;
use backend\models\search\SupportUserSearch;
use common\models\db\Support;
use Exception;
use yii\web\Response;

/**
 * Class SupportController
 * @package backend\controllers
 */
class SupportController extends AbstractController
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new SupportUserSearch();
        $dataProvider = $searchModel->search();

        $this->view->title = 'Support';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws Exception
     */
    public function actionView(int $id)
    {
        $support = Support::find()
            ->where(['id' => $id])
            ->limit(1)
            ->one();
        $this->notFound($support);

        $model = new Support();
        if ($model->addAnswer($support)) {
            $this->setSuccessFlash();
            return $this->refresh();
        }

        $searchModel = new SupportSearch();
        $dataProvider = $searchModel->search([
            'user_id' => $support->user_id,
            'federation_id' => $support->federation_id,
        ]);

        Support::updateAll(
            ['read' => time()],
            ['read' => null, 'is_question' => true, 'user_id' => $support->user_id, 'is_inside' => false]
        );

        $this->view->title = $support->user->login;
        $this->view->params['breadcrumbs'][] = ['label' => 'Support', 'url' => ['support/index']];
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'user' => $support->user,
        ]);
    }
}
