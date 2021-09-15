<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\models\db\Finance;
use common\models\db\Season;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class FinanceController
 * @package frontend\modules\federation\controllers
 */
class FinanceController extends AbstractController
{
    /**
     * @param int $id
     * @return string
     */
    public function actionIndex(int $id): string
    {
        $federation = $this->getFederation($id);

        $seasonId = Yii::$app->request->get('season_id', $this->season->id);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => Finance::find()
                ->where(['federation_id' => $id])
                ->andWhere(['season_id' => $seasonId])
                ->orderBy(['id' => SORT_DESC]),
        ]);

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.finance.title'));

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'federation' => $federation,
            'seasonId' => $seasonId,
            'seasonArray' => Season::getSeasonArray(),
        ]);
    }
}
