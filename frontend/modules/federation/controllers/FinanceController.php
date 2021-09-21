<?php

// TODO refactor

namespace frontend\modules\federation\controllers;

use common\models\db\Finance;
use common\models\db\Season;
use common\models\db\Team;
use frontend\models\forms\FederationTransferFinance;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * Class FinanceController
 * @package frontend\modules\federation\controllers
 */
class FinanceController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'transfer',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'transfer',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionTransfer(int $id)
    {
        $federation = $this->getFederation($id);
        if ($this->user->id !== $federation->president_user_id) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.federation.money-transfer.error'));
            return $this->redirect(['/federation/team/index', 'id' => $id]);
        }

        $model = new FederationTransferFinance(['federation' => $federation]);
        if ($model->execute()) {
            $this->setSuccessFlash(Yii::t('frontend', 'controllers.federation.money-transfer.success'));
            return $this->refresh();
        }

        $teamArray = Team::find()
            ->where(['!=', 'id', 0])
            ->orderBy(['name' => SORT_ASC])
            ->all();
        $teamItems = [];

        foreach ($teamArray as $team) {
            $teamItems[$team->id] = $team->fullName();
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.federation.money-transfer.title'));

        return $this->render('transfer', [
            'federation' => $federation,
            'model' => $model,
            'teamArray' => $teamItems,
        ]);
    }
}
