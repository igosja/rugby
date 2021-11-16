<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\BuildingStadium;
use common\models\db\ConstructionType;
use common\models\db\Finance;
use common\models\db\FinanceText;
use Exception;
use frontend\models\forms\StadiumDecrease;
use frontend\models\forms\StadiumIncrease;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class StadiumController
 * @package frontend\controllers
 */
class StadiumController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     */
    public function actionIncrease()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $model = new StadiumIncrease($team->stadium);

        $canChange = $team->canBuild();

        $this->setSeoTitle($team->stadium->name . '. ' . Yii::t('frontend', 'controllers.stadium.increase.title'));

        return $this->render('increase', [
            'canChange' => $canChange,
            'model' => $model,
            'team' => $team,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionDecrease()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $canChange = $team->canBuild();

        $model = new StadiumDecrease($team->stadium);

        $this->setSeoTitle($team->stadium->name . '. ' . Yii::t('frontend', 'controllers.stadium.decrease.title'));

        return $this->render('decrease', [
            'canChange' => $canChange,
            'model' => $model,
            'team' => $team,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionBuild()
    {
        if (!$this->myTeam || !$this->myTeam->canBuild()) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($team->buildingStadium) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.stadium.build.error.building'));
            return $this->redirect(['increase']);
        }

        $stadiumForm = new StadiumIncrease($team->stadium);
        if (!$stadiumForm->load(Yii::$app->request->get())) {
            return $this->redirect(['increase']);
        }

        if (!$stadiumForm->validate()) {
            $this->setErrorFlash(ErrorHelper::modelErrorsToString($stadiumForm));
            return $this->redirect(['increase']);
        }


        $buildingStadiumPrice = floor(
            (($stadiumForm->capacity ** 1.1) - ($team->stadium->capacity ** 1.1)) * StadiumIncrease::ONE_SIT_PRICE
        );
        $buildingStadiumDay = ceil(($stadiumForm->capacity - $team->stadium->capacity) / 1000);

        if ($buildingStadiumPrice > $team->finance) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.stadium.build.error.finance', [
                'price' => FormatHelper::asCurrency($buildingStadiumPrice)
            ]));
            return $this->redirect(['increase']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                $model = new BuildingStadium();
                $model->capacity = $stadiumForm->capacity;
                $model->construction_type_id = ConstructionType::BUILD;
                $model->day = $buildingStadiumDay;
                $model->team_id = $team->id;
                $model->save();

                Finance::log([
                    'capacity' => $model->capacity,
                    'finance_text_id' => FinanceText::OUTCOME_BUILDING_STADIUM,
                    'team_id' => $team->id,
                    'value' => -$buildingStadiumPrice,
                    'value_after' => $team->finance - $buildingStadiumPrice,
                    'value_before' => $team->finance,
                ]);

                $team->finance -= $buildingStadiumPrice;
                $team->save(true, ['finance']);

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.stadium.build.success'));
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            return $this->redirect(['increase']);
        }

        $message = Yii::t('frontend', 'controllers.stadium.build.message', [
            'capacity' => $stadiumForm->capacity,
            'day' => $buildingStadiumDay,
            'price' => FormatHelper::asCurrency($buildingStadiumPrice),
        ]);


        $this->setSeoTitle($team->stadium->name . '. ' . Yii::t('frontend', 'controllers.stadium.build.title'));

        return $this->render('build', [
            'message' => $message,
            'stadiumForm' => $stadiumForm,
            'team' => $team,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionDestroy()
    {
        if (!$this->myTeam || !$this->myTeam->canBuild()) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($team->buildingStadium) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.stadium.destroy.error.building'));
            return $this->redirect(['decrease']);
        }

        $stadium = new StadiumDecrease($team->stadium);
        if (!$stadium->load(Yii::$app->request->get())) {
            return $this->redirect(['decrease']);
        }

        if (!$stadium->validate()) {
            $this->setErrorFlash(ErrorHelper::modelErrorsToString($stadium));
            return $this->redirect(['decrease']);
        }

        $buildingStadiumPrice = floor(
            (($team->stadium->capacity ** 1.1) - ($stadium->capacity ** 1.1)) * StadiumDecrease::ONE_SIT_PRICE
        );
        $buildingStadiumDay = 1;

        if (Yii::$app->request->get('ok')) {
            try {
                $model = new BuildingStadium();
                $model->capacity = $stadium->capacity;
                $model->construction_type_id = ConstructionType::DESTROY;
                $model->day = $buildingStadiumDay;
                $model->team_id = $team->id;
                $model->save();

                Finance::log([
                    'capacity' => $stadium->capacity,
                    'finance_text_id' => FinanceText::INCOME_BUILDING_STADIUM,
                    'team_id' => $team->id,
                    'value' => $buildingStadiumPrice,
                    'value_after' => $team->finance + $buildingStadiumPrice,
                    'value_before' => $team->finance,
                ]);

                $team->finance += $buildingStadiumPrice;
                $team->save(true, ['finance']);

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.stadium.destroy.success'));
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            return $this->redirect(['stadium/increase']);
        }

        $message = Yii::t('frontend', 'controllers.stadium.destroy.message', [
            'capacity' => $stadium->capacity,
            'day' => $buildingStadiumDay,
            'price' => FormatHelper::asCurrency($buildingStadiumPrice),
        ]);

        $this->setSeoTitle($team->stadium->name . '. ' . Yii::t('frontend', 'controllers.stadium.destroy.title'));

        return $this->render('destroy', [
            'message' => $message,
            'model' => $stadium,
            'team' => $team,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     */
    public function actionCancel($id)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        $buildingStadium = BuildingStadium::find()
            ->where([
                'id' => $id,
                'ready' => null,
                'team_id' => $team->id,
            ])
            ->limit(1)
            ->one();
        if (!$buildingStadium) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.stadium.cancel.error'));
            return $this->redirect(['increase']);
        }

        /**
         * @var Finance $finance
         */
        $finance = Finance::find()
            ->where([
                'finance_text_id' => [FinanceText::INCOME_BUILDING_STADIUM, FinanceText::OUTCOME_BUILDING_STADIUM],
                'team_id' => $team->id,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();
        if (!$finance) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.stadium.cancel.error'));
            return $this->redirect(['increase']);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                if ($finance->value < 0) {
                    $textId = FinanceText::INCOME_BUILDING_STADIUM;
                } else {
                    $textId = FinanceText::OUTCOME_BUILDING_STADIUM;
                }

                $buildingStadium->delete();

                Finance::log([
                    'capacity' => $team->stadium->capacity,
                    'finance_text_id' => $textId,
                    'team_id' => $team->id,
                    'value' => -$finance->value,
                    'value_after' => $team->finance - $finance->value,
                    'value_before' => $team->finance,
                ]);

                $team->finance -= $finance->value;
                $team->save(true, ['finance']);

                $this->setSuccessFlash(Yii::t('frontend', 'controllers.stadium.cancel.success'));
            } catch (Throwable $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['increase']);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.stadium.cancel.title') . ' ' . $team->stadium->name);

        return $this->render('cancel', [
            'id' => $id,
            'price' => -$finance->value,
            'team' => $team,
        ]);
    }
}
