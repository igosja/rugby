<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\components\helpers\FormatHelper;
use common\models\db\Base;
use common\models\db\BaseMedical;
use common\models\db\BasePhysical;
use common\models\db\BaseSchool;
use common\models\db\BaseScout;
use common\models\db\BaseTraining;
use common\models\db\Building;
use common\models\db\BuildingBase;
use common\models\db\ConstructionType;
use common\models\db\Finance;
use common\models\db\FinanceText;
use common\models\db\Team;
use Exception;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class BaseController
 * @package frontend\controllers
 */
class BaseController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['build', 'destroy', 'cancel'],
                'rules' => [
                    [
                        'actions' => ['build', 'destroy', 'cancel'],
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
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        /**
         * @var Team $team
         */
        $team = Team::find()
            ->where(['id' => $id])
            ->one();
        $this->notFound($team);

        $linkBaseArray = [];
        $linkTrainingArray = [];
        $linkMedicalArray = [];
        $linkPhysicalArray = [];
        $linkSchoolArray = [];
        $linkScoutArray = [];

        $delBase = false;
        $delMedical = false;
        $delPhysical = false;
        $delSchool = false;
        $delScout = false;
        $delTraining = false;

        if ($this->myTeam && $this->myTeam->id === $id) {
            if ($team->buildingBase && Building::BASE === $team->buildingBase->building_id) {
                $linkBaseArray[] = Html::a(
                    'Отменить строительство',
                    ['cancel', 'id' => $team->buildingBase->id],
                    ['class' => 'btn margin']
                );
                $linkTrainingArray[] = Html::a('Идет строительство', 'javascript:', ['class' => 'btn margin']);
                $linkMedicalArray[] = Html::a('Идет строительство', 'javascript:', ['class' => 'btn margin']);
                $linkPhysicalArray[] = Html::a('Идет строительство', 'javascript:', ['class' => 'btn margin']);
                $linkSchoolArray[] = Html::a('Идет строительство', 'javascript:', ['class' => 'btn margin']);
                $linkScoutArray[] = Html::a('Идет строительство', 'javascript:', ['class' => 'btn margin']);

                $delBase = true;
                $delMedical = true;
                $delPhysical = true;
                $delSchool = true;
                $delScout = true;
                $delTraining = true;
            } else {
                if ($team->base->level < Building::MAX_LEVEL) {
                    $linkBaseArray[] = Html::a(
                        'Строить',
                        ['build', 'building' => Building::BASE],
                        ['class' => 'btn margin']
                    );
                }
                if ($team->base->level > Building::MIN_LEVEL) {
                    $linkBaseArray[] = Html::a(
                        'Разрушить',
                        ['destroy', 'building' => Building::BASE],
                        ['class' => 'btn margin']
                    );
                }

                if ($team->buildingBase && Building::TRAINING === $team->buildingBase->building_id) {
                    $linkTrainingArray[] = Html::a(
                        'Отменить строительство',
                        ['cancel', 'id' => $team->buildingBase->id],
                        ['class' => 'btn margin']
                    );

                    $delTraining = true;
                } else {
                    if ($team->baseTraining->level < Building::MAX_LEVEL) {
                        $linkTrainingArray[] = Html::a(
                            'Строить',
                            ['build', 'building' => Building::TRAINING],
                            ['class' => 'btn margin']
                        );
                    }
                    if ($team->baseTraining->level > Building::MIN_LEVEL) {
                        $linkTrainingArray[] = Html::a(
                            'Разрушить',
                            ['destroy', 'building' => Building::TRAINING],
                            ['class' => 'btn margin']
                        );
                        $linkTrainingArray[] = Html::a(
                            'Тренировка',
                            ['training/index'],
                            ['class' => 'btn margin']
                        );
                    }
                }

                if ($team->buildingBase && Building::PHYSICAL === $team->buildingBase->building_id) {
                    $linkPhysicalArray[] = Html::a(
                        'Отменить строительство',
                        ['cancel', 'id' => $team->buildingBase->id],
                        ['class' => 'btn margin']
                    );

                    $delPhysical = true;
                } else {
                    if ($team->basePhysical->level < Building::MAX_LEVEL) {
                        $linkPhysicalArray[] = Html::a(
                            'Строить',
                            ['build', 'building' => Building::PHYSICAL],
                            ['class' => 'btn margin']
                        );
                    }
                    if ($team->basePhysical->level > Building::MIN_LEVEL) {
                        $linkPhysicalArray[] = Html::a(
                            'Разрушить',
                            ['destroy', 'building' => Building::PHYSICAL],
                            ['class' => 'btn margin']
                        );
                        $linkPhysicalArray[] = Html::a(
                            'Форма',
                            ['physical/index'],
                            ['class' => 'btn margin']
                        );
                    }
                }

                if ($team->buildingBase && Building::SCHOOL === $team->buildingBase->building_id) {
                    $linkSchoolArray[] = Html::a(
                        'Отменить строительство',
                        ['cancel', 'id' => $team->buildingBase->id],
                        ['class' => 'btn margin']
                    );

                    $delSchool = true;
                } else {
                    if ($team->baseSchool->level < Building::MAX_LEVEL) {
                        $linkSchoolArray[] = Html::a(
                            'Строить',
                            ['build', 'building' => Building::SCHOOL],
                            ['class' => 'btn margin']
                        );
                    }
                    if ($team->baseSchool->level > Building::MIN_LEVEL) {
                        $linkSchoolArray[] = Html::a(
                            'Разрушить',
                            ['destroy', 'building' => Building::SCHOOL],
                            ['class' => 'btn margin']
                        );
                        $linkSchoolArray[] = Html::a(
                            'Молодежь',
                            ['school/index'],
                            ['class' => 'btn margin']
                        );
                    }
                }

                if ($team->buildingBase && Building::SCOUT === $team->buildingBase->building_id) {
                    $linkScoutArray[] = Html::a(
                        'Отменить строительство',
                        ['cancel', 'id' => $team->buildingBase->id],
                        ['class' => 'btn margin']
                    );

                    $delScout = true;
                } else {
                    if ($team->baseScout->level < Building::MAX_LEVEL) {
                        $linkScoutArray[] = Html::a(
                            'Строить',
                            ['build', 'building' => Building::SCOUT],
                            ['class' => 'btn margin']
                        );
                    }
                    if ($team->baseScout->level > Building::MIN_LEVEL) {
                        $linkScoutArray[] = Html::a(
                            'Разрушить',
                            ['destroy', 'building' => Building::SCOUT],
                            ['class' => 'btn margin']
                        );
                        $linkScoutArray[] = Html::a(
                            'Изучение',
                            ['scout/index'],
                            ['class' => 'btn margin']
                        );
                    }
                }

                if ($team->buildingBase && Building::MEDICAL === $team->buildingBase->building_id) {
                    $linkMedicalArray[] = Html::a(
                        'Отменить строительство',
                        ['cancel', 'id' => $team->buildingBase->id],
                        ['class' => 'btn margin']
                    );

                    $delMedical = true;
                } else {
                    if ($team->baseMedical->level < Building::MAX_LEVEL) {
                        $linkMedicalArray[] = Html::a(
                            'Строить',
                            ['build', 'building' => Building::MEDICAL],
                            ['class' => 'btn margin']
                        );
                    }
                    if ($team->baseMedical->level > Building::MIN_LEVEL) {
                        $linkMedicalArray[] = Html::a(
                            'Разрушить',
                            ['destroy', 'building' => Building::MEDICAL],
                            ['class' => 'btn margin']
                        );
                    }
                }
            }
        }

        $this->setSeoTitle('База команды ' . $team->fullName());

        return $this->render('view', [
            'delBase' => $delBase,
            'delMedical' => $delMedical,
            'delPhysical' => $delPhysical,
            'delSchool' => $delSchool,
            'delScout' => $delScout,
            'delTraining' => $delTraining,
            'linkBaseArray' => $linkBaseArray,
            'linkMedicalArray' => $linkMedicalArray,
            'linkPhysicalArray' => $linkPhysicalArray,
            'linkSchoolArray' => $linkSchoolArray,
            'linkScoutArray' => $linkScoutArray,
            'linkTrainingArray' => $linkTrainingArray,
            'myTeam' => $this->myTeam,
            'team' => $team,
        ]);
    }

    /**
     * @param int $building
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionBuild(int $building)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($team->buildingBase) {
            $this->setErrorFlash('На базе уже идет строительство.');
            return $this->redirect(['view', 'id' => $team->id]);
        }

        if (Building::BASE === $building) {
            $level = $team->base->level + 1;
            $base = Base::find()
                ->where(['level' => $level])
                ->limit(1)
                ->one();
            if (!$base) {
                $this->setErrorFlash('Вы имеете здание максимального уровня.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->isTraining()) {
                $this->setErrorFlash('В тренировочном центре тренируются игроки.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->isSchool()) {
                $this->setErrorFlash('В спортшколе идет подготовка игрока.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->isScout()) {
                $this->setErrorFlash('В скаутцентре идет изучение игроков.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($base->slot_min > $team->baseUsed()) {
                $this->setErrorFlash('Минимальное количество занятых слотов должно быть не меньше <span class="strong">' . $base->slot_min . '</span>.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($base->price_buy > $team->finance) {
                $this->setErrorFlash('Для строительства нужно <span class="strong">' . FormatHelper::asCurrency($base->price_buy) . '</span>.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Yii::$app->request->get('ok')) {
                try {
                    $model = new BuildingBase();
                    $model->building_id = $building;
                    $model->construction_type_id = ConstructionType::BUILD;
                    $model->day = $base->build_speed;
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'id' => $building,
                        'finance_text_id' => FinanceText::OUTCOME_BUILDING_BASE,
                        'level' => $base->level,
                        'team_id' => $team->id,
                        'value' => -$base->price_buy,
                        'value_after' => $team->finance - $base->price_buy,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance -= $base->price_buy;
                    $team->save(true, ['finance']);

                    $this->setSuccessFlash('Строительство успешно началось.');
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }
                return $this->redirect(['view', 'id' => $team->id]);
            }

            $message = 'Строительство базы <span class="strong">' . $base->level
                . '</span> уровня будет стоить <span class="strong">' . FormatHelper::asCurrency($base->price_buy)
                . '</span> и займет <span class="strong">' . $base->build_speed
                . '</span> дн.';
        } else {
            $base = Building::find()
                ->where(['id' => $building])
                ->one();

            if (!$base) {
                $this->setErrorFlash('Тип строения выбран неправильно.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Building::TRAINING === $building && $team->isTraining()) {
                $this->setErrorFlash('В тренировочном центре тренируются игроки.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Building::SCHOOL === $building && $team->isSchool()) {
                $this->setErrorFlash('В спортшколе идет подготовка игрока.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Building::SCOUT === $building && $team->isScout()) {
                $this->setErrorFlash('В скаутцентре идет изучение игроков.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            $baseLevel = 'min_base_level';
            $basePrice = 'price_buy';
            $baseSpeed = 'build_speed';

            if (Building::MEDICAL === $building) {
                $level = $team->baseMedical->level + 1;
                $base = BaseMedical::find()->where(['level' => $level]);
            } elseif (Building::PHYSICAL === $building) {
                $level = $team->basePhysical->level + 1;
                $base = BasePhysical::find()->where(['level' => $level]);
            } elseif (Building::SCHOOL === $building) {
                $level = $team->baseSchool->level + 1;
                $base = BaseSchool::find()->where(['level' => $level]);
            } elseif (Building::SCOUT === $building) {
                $level = $team->baseScout->level + 1;
                $base = BaseScout::find()->where(['level' => $level]);
            } else {
                $level = $team->baseTraining->level + 1;
                $base = BaseTraining::find()->where(['level' => $level]);
            }
            $base = $base->limit(1)->one();

            if (!$base) {
                $this->setErrorFlash('Вы имеете здание максимального уровня.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($base->$baseLevel > $team->base->level) {
                $this->setErrorFlash('Минимальный уровень базы должен быть не меньше <span class="strong">' . $base->$baseLevel . '</span>.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->base->slot_max <= $team->baseUsed()) {
                $this->setErrorFlash('На базе нет свободных слотов для строительства.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($base->$basePrice > $team->finance) {
                $this->setErrorFlash('Для строительства нужно <span class="strong">' . FormatHelper::asCurrency($base->$basePrice) . '</span>.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Yii::$app->request->get('ok')) {
                try {
                    $model = new BuildingBase();
                    $model->building_id = $building;
                    $model->construction_type_id = ConstructionType::BUILD;
                    $model->day = $base->$baseSpeed;
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'id' => $building,
                        'finance_text_id' => FinanceText::OUTCOME_BUILDING_BASE,
                        'level' => $level,
                        'team_id' => $team->id,
                        'value' => -$base->$basePrice,
                        'value_after' => $team->finance - $base->$basePrice,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance -= $base->$basePrice;
                    $team->save(true, ['finance']);

                    $this->setSuccessFlash('Строительство успешно началось.');
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                    $this->setErrorFlash();
                }
                return $this->redirect(['view', 'id' => $team->id]);
            }

            $message = 'Строительство здания <span class="strong">' . $level
                . '</span> уровня будет стоить <span class="strong">' . FormatHelper::asCurrency($base->$basePrice)
                . '</span> и займет <span class="strong">' . $base->$baseSpeed
                . '</span> дн.';
        }

        $this->setSeoTitle('Строительство базы команды ' . $team->fullName());

        return $this->render('build', [
            'building' => $building,
            'message' => $message,
            'team' => $team,
        ]);
    }

    /**
     * @param int $building
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionDestroy(int $building)
    {
        $team = $this->myTeam;
        $this->notFound($team);

        if ($team->buildingBase) {
            $this->setErrorFlash('На базе уже идет строительство.');
            return $this->redirect(['view', 'id' => $team->id]);
        }

        if (Building::BASE === $building) {
            $level = $team->base->level - 1;
            $base = Base::find()
                ->where(['level' => $level])
                ->limit(1)
                ->one();
            if (!$base) {
                $this->setErrorFlash('Вы имеете здание минимального уровня.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->isTraining()) {
                $this->setErrorFlash('В тренировочном центре тренируются игроки.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->isSchool()) {
                $this->setErrorFlash('В спортшколе идет подготовка игрока.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->isScout()) {
                $this->setErrorFlash('В скаутцентре идет изучение игроков.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($base->slot_max < $team->baseUsed()) {
                $this->setErrorFlash('Максимальное количество занятых слотов должно быть не больше <span class="strong">' . $base->slot_max . '</span>.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Yii::$app->request->get('ok')) {
                try {
                    $model = new BuildingBase();
                    $model->building_id = $building;
                    $model->construction_type_id = ConstructionType::DESTROY;
                    $model->day = 1;
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'id' => $building,
                        'finance_text_id' => FinanceText::INCOME_BUILDING_BASE,
                        'level' => $base->level,
                        'team_id' => $team->id,
                        'value' => $team->base->price_sell,
                        'value_after' => $team->finance + $team->base->price_sell,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance += $team->base->price_sell;
                    $team->save(true, ['finance']);

                    $this->setSuccessFlash('Строительство успешно началось.');
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }
                return $this->redirect(['view', 'id' => $team->id]);
            }

            $message = 'При строительстве базы <span class="strong">' . $base->level
                . '</span> уровня вы получите компенсацию <span class="strong">' . FormatHelper::asCurrency($team->base->price_sell)
                . '</span>. Это займет <span class="strong">1</span> день.';
        } else {
            $base = Building::find()
                ->where(['id' => $building])
                ->one();

            if (!$base) {
                $this->setErrorFlash('Тип строения выбран неправильно.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if ($team->baseUsed() - 1 < $team->base->slot_min) {
                $this->setErrorFlash('Минимальное количество занятых слотов должно быть не менне <span class="strong">' . $team->base->slot_min . '</span>.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Building::TRAINING === $building && $team->isTraining()) {
                $this->setErrorFlash('В тренировочном центре тренируются игроки.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Building::SCHOOL === $building && $team->isSchool()) {
                $this->setErrorFlash('В спортшколе идет подготовка игрока.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Building::SCOUT === $building && $team->isScout()) {
                $this->setErrorFlash('В скаутцентре идет изучение игроков.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Building::MEDICAL === $building) {
                $level = $team->baseMedical->level - 1;
                $price = $team->baseMedical->price_sell;
                $base = BaseMedical::find()->where(['level' => $level]);
            } elseif (Building::PHYSICAL === $building) {
                $level = $team->basePhysical->level - 1;
                $price = $team->basePhysical->price_sell;
                $base = BasePhysical::find()->where(['level' => $level]);
            } elseif (Building::SCHOOL === $building) {
                $level = $team->baseSchool->level - 1;
                $price = $team->baseSchool->price_sell;
                $base = BaseSchool::find()->where(['level' => $level]);
            } elseif (Building::SCOUT === $building) {
                $level = $team->baseScout->level - 1;
                $price = $team->baseScout->price_sell;
                $base = BaseScout::find()->where(['level' => $level]);
            } else {
                $level = $team->baseTraining->level - 1;
                $price = $team->baseTraining->price_sell;
                $base = BaseTraining::find()->where(['level' => $level]);
            }
            $base = $base->limit(1)->one();

            if (!$base) {
                $this->setErrorFlash('Вы имеете здание минимального уровня.');
                return $this->redirect(['view', 'id' => $team->id]);
            }

            if (Yii::$app->request->get('ok')) {
                try {
                    $model = new BuildingBase();
                    $model->building_id = $building;
                    $model->construction_type_id = ConstructionType::DESTROY;
                    $model->day = 1;
                    $model->team_id = $team->id;
                    $model->save();

                    Finance::log([
                        'id' => $building,
                        'finance_text_id' => FinanceText::INCOME_BUILDING_BASE,
                        'level' => $level,
                        'team_id' => $team->id,
                        'value' => $price,
                        'value_after' => $team->finance + $price,
                        'value_before' => $team->finance,
                    ]);

                    $team->finance += $price;
                    $team->save(true, ['finance']);

                    $this->setSuccessFlash('Строительство успешно началось.');
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                    $this->setErrorFlash();
                }
                return $this->redirect(['view', 'id' => $team->id]);
            }

            $message = 'При строительстве здания <span class="strong">' . $level
                . '</span> уровня вы получите компенсацию <span class="strong">' . FormatHelper::asCurrency($price)
                . '</span>. Это займет <span class="strong">1</span> день.';
        }

        $this->setSeoTitle('Разрушение базы команды ' . $team->fullName());

        return $this->render('destroy', [
            'building' => $building,
            'message' => $message,
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

        $buildingBase = BuildingBase::find()
            ->where(['id' => $id, 'ready' => null, 'team_id' => $team->id])
            ->limit(1)
            ->one();
        if (!$buildingBase) {
            $this->setErrorFlash('Строительство выбрано неправильно.');
            return $this->redirect(['view', 'id' => $team->id]);
        }

        /**
         * @var Finance $finance
         */
        $finance = Finance::find()
            ->where([
                'finance_text_id' => [FinanceText::INCOME_BUILDING_BASE, FinanceText::OUTCOME_BUILDING_BASE],
                'team_id' => $team->id,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();
        if (!$finance) {
            $this->setErrorFlash('Строительство выбрано неправильно.');
            return $this->redirect(['view', 'id' => $team->id]);
        }

        if (Yii::$app->request->get('ok')) {
            try {
                $buildingId = $buildingBase->building_id;

                if ($finance->value < 0) {
                    $textId = FinanceText::INCOME_BUILDING_BASE;
                    $level = $finance->level - 1;
                } else {
                    $textId = FinanceText::OUTCOME_BUILDING_BASE;
                    $level = $finance->level + 1;
                }

                $buildingBase->delete();

                Finance::log([
                    'id' => $buildingId,
                    'finance_text_id' => $textId,
                    'level' => $level,
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
            return $this->redirect(['view', 'id' => $team->id]);
        }

        $this->setSeoTitle('Отмена строительства базы команды ' . $team->fullName());

        return $this->render('cancel', [
            'id' => $id,
            'price' => -$finance->value,
            'team' => $team,
        ]);
    }
}
