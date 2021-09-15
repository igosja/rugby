<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\models\db\National;
use common\models\db\NationalType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 * @package frontend\modules\federation\controllers
 */
class NationalController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id): string
    {
        $federation = $this->getFederation($id);

        $query = National::find()
            ->where(['federation_id' => $id, 'national_type_id' => NationalType::MAIN])
            ->orderBy(['national_type_id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.national.title'));
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
        ]);
    }
}
