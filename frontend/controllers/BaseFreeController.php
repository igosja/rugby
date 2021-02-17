<?php

// TODO refactor

namespace frontend\controllers;

use common\components\helpers\ErrorHelper;
use common\models\db\Base;
use common\models\db\BaseMedical;
use common\models\db\BasePhysical;
use common\models\db\BaseSchool;
use common\models\db\BaseScout;
use common\models\db\BaseTraining;
use common\models\db\Building;
use common\models\db\History;
use common\models\db\HistoryText;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Response;

/**
 * Class BaseFreeController
 * @package frontend\controllers
 */
class BaseFreeController extends AbstractController
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
     * @return string|Response
     */
    public function actionView()
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

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

        if ($team->buildingBase && Building::BASE === $team->buildingBase->building_id) {
            $linkBaseArray[] = Html::a(
                Yii::t('frontend', 'controllers.base.view.link.cancel'),
                ['cancel', 'id' => $team->buildingBase->id],
                ['class' => 'btn margin']
            );
            $linkTrainingArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
            $linkMedicalArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
            $linkPhysicalArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
            $linkSchoolArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
            $linkScoutArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);

            $delBase = true;
            $delMedical = true;
            $delPhysical = true;
            $delSchool = true;
            $delScout = true;
            $delTraining = true;
        } else {
            if ($team->base->level < Building::MAX_LEVEL) {
                $linkBaseArray[] = Html::a(
                    Yii::t('frontend', 'controllers.base.view.link.build'),
                    ['build', 'building' => Building::BASE],
                    ['class' => 'btn margin']
                );
            }

            if ($team->buildingBase && Building::TRAINING === $team->buildingBase->building_id) {
                $linkTrainingArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
                $delTraining = true;
            } elseif ($team->baseTraining->level < Building::MAX_LEVEL) {
                $linkTrainingArray[] = Html::a(
                    Yii::t('frontend', 'controllers.base.view.link.build'),
                    ['build', 'building' => Building::TRAINING],
                    ['class' => 'btn margin']
                );
            }

            if ($team->buildingBase && Building::PHYSICAL === $team->buildingBase->building_id) {
                $linkPhysicalArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
                $delPhysical = true;
            } elseif ($team->basePhysical->level < Building::MAX_LEVEL) {
                $linkPhysicalArray[] = Html::a(
                    Yii::t('frontend', 'controllers.base.view.link.build'),
                    ['build', 'building' => Building::PHYSICAL],
                    ['class' => 'btn margin']
                );
            }

            if ($team->buildingBase && Building::SCHOOL === $team->buildingBase->building_id) {
                $linkSchoolArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
                $delSchool = true;
            } elseif ($team->baseSchool->level < Building::MAX_LEVEL) {
                $linkSchoolArray[] = Html::a(
                    Yii::t('frontend', 'controllers.base.view.link.build'),
                    ['build', 'building' => Building::SCHOOL],
                    ['class' => 'btn margin']
                );
            }

            if ($team->buildingBase && Building::SCOUT === $team->buildingBase->building_id) {
                $linkScoutArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
                $delScout = true;
            } elseif ($team->baseScout->level < Building::MAX_LEVEL) {
                $linkScoutArray[] = Html::a(
                    Yii::t('frontend', 'controllers.base.view.link.build'),
                    ['build', 'building' => Building::SCOUT],
                    ['class' => 'btn margin']
                );
            }

            if ($team->buildingBase && Building::MEDICAL === $team->buildingBase->building_id) {
                $linkMedicalArray[] = Html::a(Yii::t('frontend', 'controllers.base.view.link.on-build'), 'javascript:', ['class' => 'btn margin']);
                $delMedical = true;
            } elseif ($team->baseMedical->level < Building::MAX_LEVEL) {
                $linkMedicalArray[] = Html::a(
                    Yii::t('frontend', 'controllers.base.view.link.build'),
                    ['build', 'building' => Building::MEDICAL],
                    ['class' => 'btn margin']
                );
            }
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.base.view.title') . ' ' . $team->fullName());

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
     * @return string|\yii\web\Response
     */
    public function actionBuild(int $building)
    {
        if (!$this->myTeam) {
            return $this->redirect(['team/view']);
        }

        $team = $this->myTeam;

        if ($team->buildingBase) {
            $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.base'));
            return $this->redirect(['view']);
        }

        if (Building::BASE === $building) {
            $level = $team->base->level + 1;
            $base = Base::find()
                ->where(['level' => $level])
                ->limit(1)
                ->one();
            if (!$base) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.max'));
                return $this->redirect(['view']);
            }

            if ($team->isTraining()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.training'));
                return $this->redirect(['view']);
            }

            if ($team->isSchool()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.school'));
                return $this->redirect(['view']);
            }

            if ($team->isScout()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.scout'));
                return $this->redirect(['view']);
            }

            if ($base->slot_min > $team->baseUsed()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.used', ['min' => $base->slot_min]));
                return $this->redirect(['view']);
            }

            if (!$team->free_base_number) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base-free.build.error.free'));
                return $this->redirect(['view']);
            }

            if (Yii::$app->request->get('ok')) {
                try {
                    History::log([
                        'building_id' => $building,
                        'history_text_id' => HistoryText::BUILDING_UP,
                        'team_id' => $team->id,
                        'value' => $base->level,
                    ]);

                    $team->base_id++;
                    $team->free_base_number--;
                    $team->save(true, ['base_id', 'free_base_number']);

                    $this->setSuccessFlash(Yii::t('frontend', 'controllers.base.build.success'));
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                }
                return $this->redirect(['view']);
            }

            $message = Yii::t('frontend', 'controllers.base-free.build.base', ['level' => $base->level]);
        } else {
            $base = Building::find()
                ->where(['id' => $building])
                ->one();

            if (!$base) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.type'));
                return $this->redirect(['view']);
            }

            if (Building::TRAINING === $building && $team->isTraining()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.training'));
                return $this->redirect(['view']);
            }

            if (Building::SCHOOL === $building && $team->isSchool()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.school'));
                return $this->redirect(['view']);
            }

            if (Building::SCOUT === $building && $team->isScout()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.scout'));
                return $this->redirect(['view']);
            }

            $baseLevel = 'min_base_level';
            $baseTeam = $base->name . '_id';

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
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.type'));
                return $this->redirect(['view']);
            }

            if ($base->$baseLevel > $team->base->level) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.level', ['level' => $base->$baseLevel]));
                return $this->redirect(['view']);
            }

            if ($team->base->slot_max <= $team->baseUsed()) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base.build.error.slot'));
                return $this->redirect(['view']);
            }

            if (!$team->free_base_number) {
                $this->setErrorFlash(Yii::t('frontend', 'controllers.base-free.build.error.free'));
                return $this->redirect(['view']);
            }

            if (Yii::$app->request->get('ok')) {
                try {
                    History::log([
                        'building_id' => $building,
                        'history_text_id' => HistoryText::BUILDING_UP,
                        'team_id' => $team->id,
                        'value' => $level,
                    ]);

                    $team->$baseTeam++;
                    $team->free_base_number--;
                    $team->save(true, [$baseTeam, 'free_base_number']);

                    $this->setSuccessFlash(Yii::t('frontend', 'controllers.base.build.success'));
                } catch (Exception $e) {
                    ErrorHelper::log($e);
                    $this->setErrorFlash();
                }
                return $this->redirect(['view']);
            }

            $message = Yii::t('frontend', 'controllers.base-free.build.building', ['level' => $level]);
        }

        $this->setSeoTitle(Yii::t('frontend', 'controllers.base.build.title'));

        return $this->render('build', [
            'building' => $building,
            'message' => $message,
            'team' => $team,
        ]);
    }
}
