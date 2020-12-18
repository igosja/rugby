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

        $this->setSeoTitle($team->stadium->name . '. Увеличение стадиона');

        return $this->render('increase', [
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

        $model = new StadiumDecrease($team->stadium);

        $this->setSeoTitle($team->stadium->name . '. Уменьшение стадиона');

        return $this->render('decrease', [
            'model' => $model,
            'team' => $team,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionBuild()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($team->buildingStadium) {
            $this->setErrorFlash('На стадионе уже идет строительство.');
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
            $this->setErrorFlash('Для строительства нужно <span class="strong">' . FormatHelper::asCurrency($buildingStadiumPrice) . '</span>.');
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

                $this->setSuccessFlash('Строительство успешно началось.');
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            return $this->redirect(['increase']);
        }

        $message = 'Увеличение стадиона до <span class="strong">' . $stadiumForm->capacity
            . '</span> мест будет стоить <span class="strong">' . FormatHelper::asCurrency($buildingStadiumPrice)
            . '</span> и займет <span class="strong">' . $buildingStadiumDay
            . '</span> дн.';


        $this->setSeoTitle($team->stadium->name . '. Увеличение стадиона');

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
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($team->buildingStadium) {
            $this->setErrorFlash('На стадионе уже идет строительство.');
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

                $this->setSuccessFlash('Строительство успешно началось.');
            } catch (Exception $e) {
                ErrorHelper::log($e);
            }
            return $this->redirect(['stadium/increase']);
        }

        $message = 'При уменьшении стадиона до <span class="strong">' . $stadium->capacity
            . '</span> мест вы получите компенсацию <span class="strong">' . FormatHelper::asCurrency($buildingStadiumPrice)
            . '</span> и займет <span class="strong">' . $buildingStadiumDay
            . '</span> дн.';


        $this->setSeoTitle($team->stadium->name . '. Уменьшения стадиона');

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
            $this->setErrorFlash('Строительство выбрано неправильно.');
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
            $this->setErrorFlash('Строительство выбрано неправильно.');
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

                $this->setSuccessFlash('Строительство успешно отменено.');
            } catch (Throwable $e) {
                ErrorHelper::log($e);
                $this->setErrorFlash();
            }
            return $this->redirect(['increase']);
        }

        $this->setSeoTitle('Отмена строительства стадиона ' . $team->stadium->name);

        return $this->render('cancel', [
            'id' => $id,
            'price' => -$finance->value,
            'team' => $team,
        ]);
    }
}
